<html lang="pt-br"><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arcelor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&amp;display=swap" rel="stylesheet">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>

<body class="login">
    <div class="wrapper">
        <div class="pagina-inicial">
            <main class="principal">
                <section class="galeria" style="margin-left: 5em">
                    <div class="login-box"></div>
                        <form action="loginauth.php" method="POST">
                            <input type="hidden" name="csrfmiddlewaretoken" value="DkFBG8TVfXhER44wuuo3AQbM8hJoVBeiNmwOpty4UFiK0HSVYtLw8EPU5Qo2w8Ke">
                            <div class="row">
                                
                                <div class="col-12 col-lg-12" style="margin-bottom: 10px;">
                                    <label for="id_nome_login" style="color:#D9D9D9; margin-bottom: 5px;">Nome de Login</label>
                                    
                                      <input type="text" name="nome_login" class="form-control" placeholder="Ex.: João Silva" maxlength="100" required="" id="id_nome_login">
                                                                        
                                </div>
                                
                                <div class="col-12 col-lg-12" style="margin-bottom: 10px;">
                                    <label for="id_senha" style="color:#D9D9D9; margin-bottom: 5px;">Senha</label>
                                    
                                    <div style="position: relative;">
                                      <input type="password" name="senha" class="form-control" placeholder="Digite a sua senha" maxlength="70" required="" id="id_senha">
                                      <span onclick="toggleSenha('id_senha', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #555;">
                                        <!-- SVG olho aberto (padrão) -->
                                        <svg id="icon-olho" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                          <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                      </span>
                                    </div>
                                                                        
                                </div>
                                
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success col-12" style="padding: top 5px;">Logar</button>
                            </div>
                        </form>
                    </section></main></div>
                
            
        </div>
    
    <script src="assets/js/forms.js"></script>



<footer class="rodape">
    <div class="rodape__icones">
        <a href="https://www.instagram.com/jctm.ltda/?hl=pt" target="”_blank”">
            <img src="../assets/icones/1x/instagram.png" alt="ícone instagram">
        </a>
    </div>
    <p class="rodape__texto">Desenvolvido por Marcos e Lucas</p>
</footer>
</body></html>