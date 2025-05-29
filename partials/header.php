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
</head>

<header>
    <nav class="nav__grid">
        <!-- Título -->
        <div style="text-align: center">

            <!-- Menu principal -->
            <ul class="nav__list">
                <a href="../index.php"><li><?= $lang['menu_index'] ?></li></a>
                <a href="../templates/poluentesMonitorados.php"><li><?= $lang['menu_evolucao'] ?></li></a>

                <?php if (isset($_SESSION['nivel_permissao']) && $_SESSION['nivel_permissao'] === 'admin'): ?>
                    <a href="../templates/tabelaConsulta.php"><li>Relatórios de Dados</li></a>  <!-- termo mais profissional -->
                <?php endif; ?>
            </ul>
        </div>
        <!-- Botão do offcanvas -->
        <button class="btn btn-outline-light ms-auto"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight">
            <i class="fas fa-sliders-h"></i>
        </button>

        <!-- OFFCANVAS -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasRightLabel">Configurações da Conta</h5>                   <!-- texto mais específico -->
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar painel"></button>
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
                <!-- Logout -->
                <a href="../logout.php" class="btn btn-outline-primary w-100">
                    Sair do Sistema                    <!-- texto mais formal -->
                </a>
            </div>
        </div>
    </nav>
</header>