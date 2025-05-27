<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$config = include 'config.php';
$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$nome_login = $_POST['nome_login'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $conn->prepare("SELECT id, nome_login, senha_hash, nivel_permissao FROM usuarios WHERE nome_login = ?");
$stmt->bind_param("s", $nome_login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($senha, $usuario['senha_hash'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome_login'] = $usuario['nome_login'];
        $_SESSION['nivel_permissao'] = $usuario['nivel_permissao']; // ← ESSA LINHA É ESSENCIAL

        header("Location: index.php");
        exit;
    } else {
        echo "Usuário ou senha inválidos.";
        exit;
    }
} else {
    echo "Usuário ou senha inválidos.";
    exit;
}

$stmt->close();
$conn->close();
?>
