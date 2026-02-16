<?php
require_once "../../conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Coleta de dados do formulário
    $id = $_POST['id'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $tipo = $_POST['tipo'];
    $data_movimentacao = $_POST['data_movimentacao'];
    $documento_numero = $_POST['documento_numero'];
    $dedutivel_ir = isset($_POST['dedutivel_ir']) ? 1 : 0;

    try {
        // Inicia transação para garantir que ambas as tabelas sejam atualizadas juntas
        $conn->beginTransaction();

        // 2. BUSCA A OBSERVAÇÃO ANTES DE ATUALIZAR
        // Precisamos saber se este lançamento está vinculado a uma consulta
        $stmt_check = $conn->prepare("SELECT observacoes FROM financeiro WHERE id = :id");
        $stmt_check->execute([':id' => $id]);
        $obs = $stmt_check->fetchColumn();

        // 3. ATUALIZA O REGISTRO NO FINANCEIRO
        $sql_fin = "UPDATE financeiro SET 
                    descricao = :desc, 
                    valor = :valor, 
                    tipo = :tipo, 
                    data_movimentacao = :data, 
                    documento_numero = :doc, 
                    dedutivel_ir = :ir 
                    WHERE id = :id";
        
        $stmt_fin = $conn->prepare($sql_fin);
        $stmt_fin->execute([
            ':desc'  => $descricao,
            ':valor' => $valor,
            ':tipo'  => $tipo,
            ':data'  => $data_movimentacao,
            ':doc'   => $documento_numero,
            ':ir'    => $dedutivel_ir,
            ':id'    => $id
        ]);

        // 4. FECHANDO O LOOP: SINCRONIZAÇÃO COM A AGENDA
        // Se a observação contiver o "carimbo" de Consulta ID, atualizamos a agenda
        if ($obs && strpos($obs, 'Consulta ID: ') !== false) {
            
            // Extrai apenas os números da string (ex: "Consulta ID: 123" vira 123)
            $consulta_id = (int) filter_var($obs, FILTER_SANITIZE_NUMBER_INT);

            if ($consulta_id > 0) {
                // Atualiza o valor na tabela de consultas para refletir a mudança feita no financeiro
                $sql_agenda = "UPDATE consultas SET valor = :novo_valor WHERE id = :cons_id";
                $stmt_agenda = $conn->prepare($sql_agenda);
                $stmt_agenda->execute([
                    ':novo_valor' => $valor,
                    ':cons_id'    => $consulta_id
                ]);
            }
        }

        // Se tudo deu certo, confirma as alterações
        $conn->commit();
        
        // Redireciona de volta para o fluxo de caixa com aviso de sucesso
        header("Location: fluxo-caixa.php?sucesso=editado");
        exit;

    } catch (PDOException $e) {
        // Em caso de erro, desfaz qualquer alteração nas duas tabelas
        $conn->rollBack();
        die("Erro crítico ao sincronizar dados: " . $e->getMessage());
    }
} else {
    // Se tentarem acessar o arquivo diretamente sem POST, redireciona
    header("Location: fluxo-caixa.php");
    exit;
}