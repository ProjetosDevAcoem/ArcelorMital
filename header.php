<header>
    <nav class="nav__grid">
        <a class="img" href="https://prefeitura.poa.br/">
            <img src="src/images/prefeitura Porto Alegre.svg" alt="" />
        </a>
        <div style="text-align: center">
            <h1><?= $lang['title'] ?></h1>
            <ul class="nav__list">
                <a href="index.php"><li><?= $lang['menu_index'] ?></li></a>
                <a href="saibamais.php"><li><?= $lang['menu_saiba'] ?></li></a>
                <a href="dadosMeteorologicos.php"><li><?= $lang['menu_dados'] ?></li></a>
                <a href="poluentesMonitorados.php"><li><?= $lang['menu_evolucao'] ?></li></a>
            </ul>
        </div>
        <form method="get" id="languageForm" class="idiomaBotao">
            <select name="lang" onchange="this.form.submit()">
                <option value="pt" <?= $_SESSION['lang'] == 'pt' ? 'selected' : '' ?>>🇧🇷 <?= $lang['lang_pt'] ?></option>
                <option value="en" <?= $_SESSION['lang'] == 'en' ? 'selected' : '' ?>>🇺🇸 <?= $lang['lang_en'] ?></option>
                <option value="es" <?= $_SESSION['lang'] == 'es' ? 'selected' : '' ?>>🇪🇸 <?= $lang['lang_es'] ?></option>
            </select>
        </form>
    </nav>
</header>
