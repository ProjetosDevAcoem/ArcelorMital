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
<html lang="pt-br"><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcelor Mital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&amp;display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/menu-lateral.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>

<body>
    <div class="pagina-inicial">
        <header class="cabecalho">
        </header>    
        <main class="principal">       
        
    <section class="galeria" style="margin-left: 5em">
    <form action="criarconta.php" method="POST">
    <div class="row">
        <div class="col-12" style="margin-bottom: 10px;">
            <label>Nome de Cadastro</label>
            <input type="text" name="nome_cadastro" class="form-control" required>
        </div>

        <div class="col-12" style="margin-bottom: 10px;">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="col-12" style="margin-bottom: 10px;">
            <label>Senha</label>
            <input type="password" name="senha_1" class="form-control" required>
        </div>

        <div class="col-12" style="margin-bottom: 10px;">
            <label>Confirme a Senha</label>
            <input type="password" name="senha_2" class="form-control" required>
        </div>

        <div class="col-12" style="margin-bottom: 10px;">
            <label>Nível de acesso</label>
            <select name="nivel_permissao" class="form-control" required>
                <option value="" disabled selected hidden>Selecione o grupo</option>
                <option value="admin">Administrador</option>
                <option value="leitor">Leitor</option>
                <option value="editor">Editor</option>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-success col-12">Cadastrar</button>
</form>

    </section>

    <script src="/static/js/forms.js"></script>

        
    <footer class="rodape">
        <div class="rodape__icones">
            <a href="https://www.instagram.com/jctm.ltda/?hl=pt" target="”_blank”">
                <img src="/assets/icones/1x/instagram.png" alt="ícone instagram">
            </a>
        </div>
        <p class="rodape__texto">Desenvolvido por Marcos e Lucas</p>
    </footer>
        <script src="/assets/js/forms.js"></script>


    </main>
    </div>
</body>
</html>