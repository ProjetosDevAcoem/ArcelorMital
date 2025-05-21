<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include 'phpconfig.php';
include 'head.php';
include 'header.php';
include 'graficos/carregarpaginainicial.php';
include 'tabelaDados.php';
?>
<head>
        
    <!-- Estilo Personalizado -->
        <link rel="stylesheet" href="assets/css/normalize.css">
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" href="assets/css/cabecalho.css">
        <link rel="stylesheet" href="assets/css/grid/cabecalho-grid.css">
        <link rel="stylesheet" href="assets/css/mapa.css">
        <link rel="stylesheet" href="assets/css/responsivo.css">
        <link rel="stylesheet" href="assets/css/calendario.css">
        <link rel="stylesheet" href="assets/css/rodape.css">
        <link rel="stylesheet" href="assets/css/cabecalho-botoes.css">
        <link rel="stylesheet" href="assets/css/grid/elementosAQI.css">
        <link rel="stylesheet" href="assets/css/lateral-direita.css">
        <link rel="stylesheet" href="assets/css/enhanced-style.css">
        <link rel="stylesheet" href="assets/css/meteo.css">
        <link rel="stylesheet" href="assets/css/monitoring.css">
        <link rel="stylesheet" href="assets/css/tabela.css">

        <title id="page-title">Usuarios</title>

</head>
<body>
    <main class="monitoring-container">
        <section class="filter-section">
            <div>
                <div style="display: flex; justify-content: space-between;">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Usuários
                    </h2>
                </div>
            </div>
            <div class="mt-5">
                <div class="table-responsive shadow rounded-3">
                    <table class="table table-hover table-bordered align-middle text-center bg-white">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Email</th>
                                <th scope="col">Nível de Permissão</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($linha = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($linha['id']) ?></td>
                                        <td><?= htmlspecialchars($linha['nome_login']) ?></td>
                                        <td><?= htmlspecialchars($linha['email']) ?></td>
                                        <td><?= htmlspecialchars($linha['nivel_permissao']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4">Nenhum usuário encontrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <!-- Rodapé -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Acoem. <?= $lang['footer_rights'] ?></p>
        </div>
        <div id="FooterSeals">
            <a href="https://www.acoem.com/brasil/pt-br/" target="_blank" rel="noopener noreferrer">
                <img src="src/images/logo-acoem.svg" alt="Logo Acoem" style="margin: 20px;" />
            </a>
            <div class="secure-seal">
                <span id="ss_img_wrapper_115-55_image_en">
                    <a href="http://www.alphassl.com/ssl-certificates/wildcard-ssl.html" target="_blank"
                        title="SSL Certificates">
                        <img alt="<?= $lang['footer_ssl_alt'] ?>" id="ss_img"
                            src="//seal.alphassl.com/SiteSeal/images/alpha_noscript_115-55_en.gif"
                            title="SSL Certificate">
                    </a>
                </span>
                <script type="text/javascript" src="//seal.alphassl.com/SiteSeal/alpha_image_115-55_en.js"></script>
            </div>
        </div>
    </footer>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="index.js"></script>
    <script src="assets/js/botaoIdioma.js"></script>
    <script src="tabela.js"></script>
    <script src="requests.js" type="module"></script>
    <script src="assets/js/graficos/gerarGraficoAQI.js"></script>
    <script src="assets/js/chartsTest.js"></script>
    <script src="assets/js/baixarDados.js"></script>
</body>

</html>