<?php
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
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<header>
    <nav class="nav__grid">
        
        <button></button>

        <!-- Título -->
        <div style="text-align: center">
            <h1><?= $lang['title'] ?></h1>

            <!-- Menu principal -->
            <ul class="nav__list">
                <a href="../index.php"><li><?= $lang['menu_index'] ?></li></a>
                <a href="../templates/poluentesMonitorados.php"><li><?= $lang['menu_evolucao'] ?></li></a>

                <?php if (isset($_SESSION['nivel_permissao']) && $_SESSION['nivel_permissao'] === 'admin'): ?>
                    <a href="../templates/tabelaConsulta.php"><li>Tabela</li></a>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Botão do offcanvas -->
        <button class="btn btn- " type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            <i class="fas fa-cog"></i>
        </button>

        <!-- OFFCANVAS -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasRightLabel">Configurações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
            </div>
            <div class="offcanvas-body">

                <!-- Formulário de troca de idioma -->
                <form method="get" id="languageForm" class="mb-3">
                    <select class="form-select" name="lang" onchange="this.form.submit()">
                        <option value="pt" <?= $_SESSION['lang'] == 'pt' ? 'selected' : '' ?>>🇧🇷 <?= $lang['lang_pt'] ?></option>
                        <option value="en" <?= $_SESSION['lang'] == 'en' ? 'selected' : '' ?>>🇺🇸 <?= $lang['lang_en'] ?></option>
                        <option value="es" <?= $_SESSION['lang'] == 'es' ? 'selected' : '' ?>>🇪🇸 <?= $lang['lang_es'] ?></option>
                    </select>
                </form>

                <!-- Botão para abrir cadastrar.php em nova aba -->
                <?php if (isset($_SESSION['nivel_permissao']) && $_SESSION['nivel_permissao'] === 'admin'): ?>
                    <a href="../cadastrar.php" target="_blank" class="btn btn-outline-primary w-100 mb-3">
                        Cadastrar novos usuários
                    </a>
                <?php endif; ?>
                <!-- Logout -->
                <a href="logout.php" target="_blank" class="btn btn-outline-primary w-100"><li><?= $lang['menu_logout'] ?></li></a>
            </div>
        </div>
    </nav>
</header>
