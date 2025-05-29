<?php
session_start();

// Sempre retorna JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido']);
    exit;
}

// Verifica o token CSRF
if (
    !isset($_POST['csrfmiddlewaretoken']) ||
    !isset($_SESSION['csrf_token']) ||
    $_POST['csrfmiddlewaretoken'] !== $_SESSION['csrf_token']
) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Token CSRF inválido.']);
    exit;
}

// Conecta ao banco
$config = include 'config.php';
$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

if ($conn->connect_error) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro de conexão com o banco de dados.']);
    exit;
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
        $_SESSION['nivel_permissao'] = $usuario['nivel_permissao'];

        echo json_encode(['status' => 'ok']);
        exit;
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário ou senha inválidos.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário ou senha inválidos.']);
    exit;
}

$stmt->close();
$conn->close();
