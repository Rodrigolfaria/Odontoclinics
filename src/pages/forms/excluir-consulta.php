<?php
require_once "../../conexao.php";

// 1. Verifica se o ID foi passado via URL
if (!isset($_GET['id'])) {
    header("Location: listar-consultas.php");
    exit;
}

$id = $_GET['id'];
// Captura a decisão do segundo prompt (1 para sim, 0 para não)
$excluir_fin = isset($_GET['excluir_fin']) ? (int)$_GET['excluir_fin'] : 0;
$referencia = "Consulta ID: " . $id;

try {
    // Inicia uma transação para garantir que ou apaga tudo ou não apaga nada
    $conn->beginTransaction();

    // 2. Se o usuário confirmou no prompt, excluímos o lançamento no financeiro
    if ($excluir_fin === 1) {
        $sql_fin = "DELETE FROM financeiro WHERE observacoes = :ref";
        $stmt_fin = $conn->prepare($sql_fin);
        $stmt_fin->execute([':ref' => $referencia]);
    }

    // 3. Exclui o agendamento na tabela de consultas
    $sql_cons = "DELETE FROM consultas WHERE id = :id";
    $stmt_cons = $conn->prepare($sql_cons);
    $stmt_cons->execute([':id' => $id]);

    // Confirma as alterações no banco
    $conn->commit();

    // Redireciona com uma mensagem de sucesso
    header("Location: listar-consultas.php?status=excluido");
    exit;

} catch (PDOException $e) {
    // Caso ocorra qualquer erro, desfaz as alterações
    $conn->rollBack();
    die("Erro ao excluir consulta: " . $e->getMessage());
}
?>