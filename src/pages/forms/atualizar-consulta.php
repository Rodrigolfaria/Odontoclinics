<?php
require_once "../../conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura dos dados do formulário
    $id = $_POST['id'];
    $paciente_id = $_POST['paciente_id'];
    $data_consulta = $_POST['data_consulta'];
    $horario_consulta = $_POST['horario_consulta'];
    $tipo_procedimento = $_POST['tipo_procedimento'];
    $status = $_POST['status'];
    $valor = $_POST['valor'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $status_pagamento = $_POST['status_pagamento'];
    $observacoes = $_POST['observacoes'];
    
    // Novos campos (fazendo a ponte entre o formulário e o banco)
    $especialidade = $_POST['especialidade'] ?? null;
    $dentista = $_POST['dentista_responsavel'] ?? null; 
    $data_retorno = !empty($_POST['data_retorno']) ? $_POST['data_retorno'] : null;

    try {
        $conn->beginTransaction();

        // 1. Atualiza a tabela de consultas
        // Nota: As colunas aqui batem com o seu comando 'Change/Drop' (especialidade e dentista)
        $sql = "UPDATE consultas SET 
                paciente_id = :paciente_id, 
                especialidade = :especialidade,
                dentista = :dentista,
                data_consulta = :data_consulta, 
                horario_consulta = :horario_consulta, 
                tipo_procedimento = :tipo_procedimento, 
                status = :status, 
                valor = :valor, 
                forma_pagamento = :forma_pagamento, 
                status_pagamento = :status_pagamento, 
                observacoes = :observacoes,
                data_retorno = :data_retorno 
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':paciente_id' => $paciente_id,
            ':especialidade' => $especialidade,
            ':dentista' => $dentista,
            ':data_consulta' => $data_consulta,
            ':horario_consulta' => $horario_consulta,
            ':tipo_procedimento' => $tipo_procedimento,
            ':status' => $status,
            ':valor' => $valor,
            ':forma_pagamento' => $forma_pagamento,
            ':status_pagamento' => $status_pagamento,
            ':observacoes' => $observacoes,
            ':data_retorno' => $data_retorno,
            ':id' => $id
        ]);

        // --- LÓGICA FINANCEIRA SINCRONIZADA ---
        $referencia = "Consulta ID: " . $id;

        if ($status_pagamento == 'Pago') {
            // Verifica se já existe um lançamento para esta consulta
            $checkFin = $conn->prepare("SELECT id FROM financeiro WHERE observacoes = :ref");
            $checkFin->execute([':ref' => $referencia]);
            $financeiro = $checkFin->fetch(PDO::FETCH_ASSOC);

            if ($financeiro) {
                // Atualiza lançamento existente
                $sql_upd_fin = "UPDATE financeiro SET 
                                valor = :valor, 
                                data_movimentacao = :data 
                                WHERE id = :fin_id";
                $stmt_upd_fin = $conn->prepare($sql_upd_fin);
                $stmt_upd_fin->execute([
                    ':valor' => $valor,
                    ':data'  => $data_consulta,
                    ':fin_id' => $financeiro['id']
                ]);
            } else {
                // Busca nome do paciente para a descrição do financeiro
                $stmtP = $conn->prepare("SELECT nome FROM pacientes WHERE id = :p_id");
                $stmtP->execute([':p_id' => $paciente_id]);
                $nome_p = $stmtP->fetchColumn();

                // Insere novo lançamento como Receita
                $sql_ins_fin = "INSERT INTO financeiro (descricao, valor, tipo, categoria, data_movimentacao, observacoes) 
                                VALUES (:desc, :valor, 'Receita', 'Consulta', :data, :ref)";
                $stmt_ins_fin = $conn->prepare($sql_ins_fin);
                $stmt_ins_fin->execute([
                    ':desc' => "Recebimento: " . ($nome_p ? $nome_p : "Paciente ID ".$paciente_id),
                    ':valor' => $valor,
                    ':data'  => $data_consulta,
                    ':ref' => $referencia
                ]);
            }
        } else {
            // Se o status NÃO for 'Pago', remove do financeiro para evitar duplicidade ou erro de saldo
            $sql_del_fin = "DELETE FROM financeiro WHERE observacoes = :ref";
            $stmt_del_fin = $conn->prepare($sql_del_fin);
            $stmt_del_fin->execute([':ref' => $referencia]);
        }

        $conn->commit();
        header("Location: listar-consultas.php?sucesso=1");
        exit;

    } catch (PDOException $e) {
        $conn->rollBack();
        die("Erro ao atualizar banco de dados: " . $e->getMessage());
    }
} else {
    header("Location: listar-consultas.php");
    exit;
}