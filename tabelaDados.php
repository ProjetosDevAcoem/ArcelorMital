<?php
// Conexão com banco (ajuste com base no seu config.php)
$config = include 'config.php';
$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Inserção no banco
$stmt = $conn->prepare("SELECT * FROM usuarios");
$stmt -> execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();
?>
