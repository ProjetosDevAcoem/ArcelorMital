<?php
// Não inicia a sessão aqui, assumindo que já foi iniciada antes

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<header>
    <nav class="nav__grid">
        <div>
            <form method="get" id="languageForm" class="idiomaBotao">
                <select name="lang" onchange="this.form.submit()">
                    <option value="pt" <?= $_SESSION['lang'] == 'pt' ? 'selected' : '' ?>>🇧🇷 <?= $lang['lang_pt'] ?></option>
                    <option value="en" <?= $_SESSION['lang'] == 'en' ? 'selected' : '' ?>>🇺🇸 <?= $lang['lang_en'] ?></option>
                    <option value="es" <?= $_SESSION['lang'] == 'es' ? 'selected' : '' ?>>🇪🇸 <?= $lang['lang_es'] ?></option>
                </select>
            </form>
        </div>
        <div style="text-align: center">
            <h1><?= $lang['title'] ?></h1>
            <ul class="nav__list">
                <a href="index.php"><li><?= $lang['menu_index'] ?></li></a>
                <a href="saibamais.php"><li><?= $lang['menu_saiba'] ?></li></a>
                <a href="dadosMeteorologicos.php"><li><?= $lang['menu_dados'] ?></li></a>
                <a href="poluentesMonitorados.php"><li><?= $lang['menu_evolucao'] ?></li></a>

                <?php if (isset($_SESSION['nivel_permissao']) && $_SESSION['nivel_permissao'] === 'admin'): ?>
                    <a href="tabelaConsulta.php"><li>Tabela</li></a>
                <?php endif; ?>

                <?php if (isset($_SESSION['nivel_permissao']) && $_SESSION['nivel_permissao'] === 'admin'): ?>
                    <a href="cadastrar.php"><li>Cadastrar</li></a>
                <?php endif; ?>
            </ul>
        </div>
        <div>
            <ul class="nav__list">
                <a href="logout.php"><li><?= $lang['menu_logout'] ?></li></a>
            </ul>
        </div>
    </nav>
</header>
