<?php
require_once "../../conexao.php";

// 1. Verifica se o ID foi passado via URL (GET)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 2. Prepara a remoção
        $stmt = $conn->prepare("DELETE FROM pacientes WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // 3. Redireciona de volta com uma mensagem de sucesso
            echo "<script>
                    alert('Paciente removido com sucesso!');
                    window.location.href = 'listar-pacientes.php';
                  </script>";
        }
    } catch (PDOException $e) {
        die("Erro ao excluir: " . $e->getMessage());
    }
} else {
    header("Location: listar-pacientes.php");
    exit;
}