<?php
require_once "../../conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // 1. Coleta os dados do POST
        $descricao        = $_POST['descricao'];
        $valor            = $_POST['valor'];
        $tipo             = $_POST['tipo'];
        $categoria        = $_POST['categoria'];
        $data_mov         = $_POST['data_movimentacao'];
        $doc_tipo         = $_POST['documento_tipo'];
        $doc_num          = $_POST['documento_numero'];
        $observacoes      = $_POST['observacoes'];
        
        // 2. Trata o checkbox (se não marcado, o PHP não recebe a chave, então definimos 0)
        $dedutivel_ir     = isset($_POST['dedutivel_ir']) ? 1 : 0;

        // 3. Prepara o SQL
        $sql = "INSERT INTO financeiro (
                    descricao, valor, tipo, categoria, documento_tipo, 
                    documento_numero, data_movimentacao, dedutivel_ir, observacoes
                ) VALUES (
                    :descricao, :valor, :tipo, :categoria, :doc_tipo, 
                    :doc_num, :data_mov, :dedutivel, :obs
                )";

        $stmt = $conn->prepare($sql);

        // 4. Vincula os parâmetros
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':doc_tipo', $doc_tipo);
        $stmt->bindParam(':doc_num', $doc_num);
        $stmt->bindParam(':data_mov', $data_mov);
        $stmt->bindParam(':dedutivel', $dedutivel_ir);
        $stmt->bindParam(':obs', $observacoes);

        // 5. Executa e redireciona
        if ($stmt->execute()) {
            // Redireciona para o fluxo de caixa com uma mensagem de sucesso (opcional)
            header("Location: fluxo-caixa.php?status=sucesso");
            exit;
        } else {
            echo "Erro ao gravar os dados.";
        }

    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }
} else {
    // Se tentarem acessar o arquivo direto via URL, volta para o formulário
    header("Location: novo-lancamento.php");
    exit;
}