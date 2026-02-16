<?php
require_once "../../conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // 1. Coleta e limpeza dos dados do formulário
        $paciente_id      = $_POST['paciente_id'];
        $data_consulta    = $_POST['data_consulta'];
        $horario_consulta = $_POST['horario_consulta'];
        $especialidade    = $_POST['especialidade']; 
        $dentista         = $_POST['dentista'];      
        $tipo_procedimento = $_POST['tipo_procedimento'];
        
        // Dados Financeiros com tratamento para valores vazios
        $valor            = !empty($_POST['valor']) ? $_POST['valor'] : 0;
        
        // CORREÇÃO AQUI: Se estiver vazio, envia NULL em vez de "" para evitar erro de ENUM
        $forma_pagamento  = !empty($_POST['forma_pagamento']) ? $_POST['forma_pagamento'] : null;
        
        $status_pagamento = $_POST['status_pagamento'];
        $observacoes      = $_POST['observacoes'];

        // 2. Preparação do SQL
        $sql = "INSERT INTO consultas (
                    paciente_id, 
                    data_consulta, 
                    horario_consulta, 
                    especialidade, 
                    dentista, 
                    tipo_procedimento, 
                    valor, 
                    forma_pagamento, 
                    status_pagamento, 
                    observacoes, 
                    status
                ) VALUES (
                    :paciente_id, 
                    :data_consulta, 
                    :horario_consulta, 
                    :especialidade, 
                    :dentista, 
                    :tipo_procedimento, 
                    :valor, 
                    :forma_pagamento, 
                    :status_pagamento, 
                    :observacoes, 
                    'Agendado'
                )";

        $stmt = $conn->prepare($sql);

        // 3. Vinculação dos parâmetros (Bind)
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':data_consulta', $data_consulta);
        $stmt->bindParam(':horario_consulta', $horario_consulta);
        $stmt->bindParam(':especialidade', $especialidade);
        $stmt->bindParam(':dentista', $dentista);
        $stmt->bindParam(':tipo_procedimento', $tipo_procedimento);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':forma_pagamento', $forma_pagamento); // Agora pode ser NULL
        $stmt->bindParam(':status_pagamento', $status_pagamento);
        $stmt->bindParam(':observacoes', $observacoes);

        // 4. Execução e Redirecionamento
        if ($stmt->execute()) {
            header("Location: listar-consultas.php?sucesso=1");
            exit();
        } else {
            echo "Erro ao gravar os dados.";
        }

    } catch (PDOException $e) {
        die("Erro crítico no banco de dados: " . $e->getMessage());
    }
} else {
    header("Location: agendar-consulta.php");
    exit();
}