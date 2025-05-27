<?php
session_start();

if (
    !isset($_SESSION['usuario_id']) ||
    !isset($_SESSION['nivel_permissao']) ||
    $_SESSION['nivel_permissao'] !== 'admin'
) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro • Arcelor Mital</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/SEU_KIT_AQUI.js" crossorigin="anonymous"></script>

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            font-family: 'Poppins', sans-serif;
        }
        .card-register {
            max-width: 500px;
            margin: auto;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .card-register .form-control:focus {
            border-color: #4e54c8;
            box-shadow: 0 0 0 .2rem rgba(78,84,200,0.25);
        }
        .btn-primary {
            background: #4e54c8;
            border: none;
        }
        .btn-primary:hover {
            background: #3b3fc7;
        }
        .input-group-text {
            background: #fff;
            border-right: 0;
        }
        .form-control {
            border-left: 0;
        }
        h2 {
            color: #4e54c8;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card card-register animate__animated animate__fadeIn">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Cadastro de Usuário</h2>
                <form action="criarconta.php" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="nome_cadastro" class="form-label">Nome de Cadastro</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="nome_cadastro" name="nome_cadastro"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="senha_1" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="senha_1" name="senha_1"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="senha_2" class="form-label">Confirme a Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="senha_2" name="senha_2"
                                   class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="nivel_permissao" class="form-label">Nível de Acesso</label>
                        <select id="nivel_permissao" name="nivel_permissao"
                                class="form-select" required>
                            <option value="" disabled selected hidden>Selecione o grupo</option>
                            <option value="admin">Administrador</option>
                            <option value="leitor">Leitor</option>
                            <option value="editor">Editor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2zJG+oFFf1lC5eHnrn54papBu3BxUrrZxQo8lty5FfKKk2e2CkFZHpG3w4X"
        crossorigin="anonymous"></script>

    <script>
        // validação simples bootstrap
        (function () {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>