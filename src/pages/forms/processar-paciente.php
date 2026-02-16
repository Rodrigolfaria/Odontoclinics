<?php
// 1. Inclui a conexão (caminho relativo para src/)
require_once "../../conexao.php";

// 2. Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    /**
     * Função para tratar campos vazios.
     * Se o usuário não preencher, o PHP envia NULL para o MySQL,
     * evitando erros de formato em campos de DATA ou NUMÉRICOS.
     */
    function tratarCampo($valor) {
        $valor = trim($valor); // Remove espaços extras
        return ($valor === "") ? null : $valor;
    }

    try {
        // 3. Prepara a Query SQL profissional (PDO)
        $sql = "INSERT INTO pacientes (
                    nome, cpf, data_nascimento, email, telefone, 
                    genero, cep, logradouro, numero, bairro, 
                    complemento, cidade, estado, anamnese
                ) VALUES (
                    :nome, :cpf, :data_nascimento, :email, :telefone, 
                    :genero, :cep, :logradouro, :numero, :bairro, 
                    :complemento, :cidade, :estado, :anamnese
                )";

        $stmt = $conn->prepare($sql);

        // 4. Mapeia os dados do formulário tratando os vazios
        $stmt->bindValue(':nome',            $_POST['nome']); // Nome é obrigatório (HTML required)
        $stmt->bindValue(':cpf',             tratarCampo($_POST['cpf']));
        $stmt->bindValue(':data_nascimento', tratarCampo($_POST['data_nascimento']));
        $stmt->bindValue(':email',           tratarCampo($_POST['email']));
        $stmt->bindValue(':telefone',        tratarCampo($_POST['telefone']));
        $stmt->bindValue(':genero',          tratarCampo($_POST['genero'] ?? ''));
        $stmt->bindValue(':cep',             tratarCampo($_POST['cep']));
        $stmt->bindValue(':logradouro',      tratarCampo($_POST['logradouro']));
        $stmt->bindValue(':numero',          tratarCampo($_POST['numero']));
        $stmt->bindValue(':bairro',          tratarCampo($_POST['bairro']));
        $stmt->bindValue(':complemento',     tratarCampo($_POST['complemento']));
        $stmt->bindValue(':cidade',          tratarCampo($_POST['cidade']));
        $stmt->bindValue(':estado',          tratarCampo($_POST['estado']));
        $stmt->bindValue(':anamnese',        tratarCampo($_POST['anamnese']));

        // 5. Executa a gravação
        if ($stmt->execute()) {
            echo "<script>
                    alert('Paciente cadastrado com sucesso!');
                    window.location.href = 'listar-pacientes.php';
                  </script>";
        }

    } catch (PDOException $e) {
        // Em produção, você salvaria isso num log. No desenvolvimento, exibimos o erro.
        die("Erro crítico ao salvar no banco: " . $e->getMessage());
    }

} else {
    // Bloqueia acesso direto via URL
    header("Location: cadastrar-paciente.php");
    exit;
}