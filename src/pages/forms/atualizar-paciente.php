<?php
require_once "../../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $sql = "UPDATE pacientes SET 
                nome = :nome, 
                cpf = :cpf, 
                data_nascimento = :data_nascimento
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $_POST['id']);
        $stmt->bindValue(':nome', $_POST['nome']);
        $stmt->bindValue(':cpf', $_POST['cpf'] ?: null);
        $stmt->bindValue(':data_nascimento', $_POST['data_nascimento'] ?: null);

        if ($stmt->execute()) {
            echo "<script>alert('Dados atualizados!'); window.location.href='listar-pacientes.php';</script>";
        }
    } catch (PDOException $e) {
        die("Erro ao atualizar: " . $e->getMessage());
    }
}