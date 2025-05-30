<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include(__DIR__ . '/../lang/langConfig.php');
?>
<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-…"
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        header {
            background: #545a66;
            height: 50px;
            display: flex;
            align-items: center;
            padding: 0;
        }
        .nav__grid {
            display: flex;
            align-items: center;
            justify-content: space-between; /* separa esquerda e direita */
            height: 100%;
            width: 100%;
            padding: 0 10px;
        }
        .nav__list {
            display: flex;
            align-items: center;
            height: 100%;
            margin: 0;
            padding: 0;
            flex: 1;
            justify-content: flex-start; /* menu à esquerda */
        }
        .nav__list a {
            display: flex;
            align-items: center;
            height: 50px;
            padding: 0 18px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.2s, color 0.2s;
        }
        .nav__list a.active,
        .nav__list a:hover,
        .nav__list a:focus {
            background: #f37016;
            color: #fff;
        }
        .nav__list li {
            list-style: none;
            padding: 0;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        #languageForm {
            margin-bottom: 0;
        }
        #languageForm .form-select {
            height: 28px;
            padding: 2px 8px;
            font-size: 14px;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-outline-light {
            border: none;
            color: #fff;
            margin-left: 10px;
        }
        .btn-outline-light i {
            transition: color 0.3s;
            font-size: 18px;
        }
        .btn-outline-light:focus i,
        .btn-outline-light[aria-expanded="true"] i {
            color: orange !important;
        }
    </style>
</head>

<header>
    <nav class="nav__grid">
        <!-- Menu à esquerda -->
       <ul class="nav__list">
                <a href="../index.php"><li><?= $lang['menu_index'] ?></li></a>
                <a href="../templates/poluentesMonitorados.php"><li><?= $lang['menu_evolucao'] ?></li></a>

                <?php if (isset($_SESSION['nivel_permissao']) && $_SESSION['nivel_permissao'] === 'admin'): ?>
                    <a href="../templates/tabelaConsulta.php"><li>Relatórios de Dados</li></a>  <!-- termo mais profissional -->
                <?php endif; ?>
            </ul>
        <!-- Idioma e botão à direita -->
        <div class="header-right">
            <form method="get" id="languageForm" style="display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-globe" style="color: #fff; font-size: 18px;"></i>
                <select class="form-select" name="lang" onchange="this.form.submit()">
                    <option value="pt" <?= $_SESSION['lang'] == 'pt' ? 'selected' : '' ?>>Português</option>
                    <option value="en" <?= $_SESSION['lang'] == 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= $_SESSION['lang'] == 'es' ? 'selected' : '' ?>>Español</option>
                </select>
            </form>
            <button class="btn btn-outline-light"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasRight"
                    aria-controls="offcanvasRight">
                <i class="fas fa-cog"></i>
            </button>
        </div>
        <!-- OFFCANVAS -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasRightLabel">Configurações da Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar painel"></button>
            </div>
            <div class="offcanvas-body">
                <a href="../logout.php" class="btn btn-outline-primary w-100">
                    Sair do Sistema
                </a>
            </div>
        </div>
    </nav>
</header>
