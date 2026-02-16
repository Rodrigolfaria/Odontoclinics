<?php
require_once "../../conexao.php";

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepara a eliminação
        $stmt = $conn->prepare("DELETE FROM financeiro WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Sucesso: Redireciona com parâmetro de status
            header("Location: fluxo-caixa.php?status=excluido");
            exit;
        } else {
            echo "Erro ao tentar excluir o lançamento.";
        }
    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }
} else {
    // Se não houver ID, volta para o fluxo de caixa
    header("Location: fluxo-caixa.php");
    exit;
}