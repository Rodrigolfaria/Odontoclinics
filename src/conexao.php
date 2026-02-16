<?php
// Configurações do MAMP
$host = "localhost";
$user = "root";
$pass = "root"; // No MAMP a senha padrão costuma ser root
$db   = "odontoclinics";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Configura para mostrar erros (essencial para o desenvolvedor)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>