<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include '../lang/langConfig.php';
include '../partials/head.php';
include '../partials/header.php';
?>
<?php include '../graficos/gerarGraficoAQI.php'; ?>
<body>
<head>
        
        <!-- Estilo Personalizado -->
            <link rel="stylesheet" href="../assets/css/normalize.css">
            <link rel="stylesheet" href="../assets/css/style.css" />
            <link rel="stylesheet" href="../assets/css/cabecalho.css">
            <link rel="stylesheet" href="../assets/css/grid/cabecalho-grid.css">
            <link rel="stylesheet" href="../assets/css/mapa.css">
            <link rel="stylesheet" href="../assets/css/responsivo.css">
            <link rel="stylesheet" href="../assets/css/calendario.css">
            <link rel="stylesheet" href="../assets/css/rodape.css">
            <link rel="stylesheet" href="../assets/css/cabecalho-botoes.css">
            <link rel="stylesheet" href="../assets/css/grid/elementosAQI.css">
            <link rel="stylesheet" href="../assets/css/lateral-direita.css">
            <link rel="stylesheet" href="../assets/css/enhanced-style.css">
            <link rel="stylesheet" href="../assets/css/meteo.css">
            <link rel="stylesheet" href="../assets/css/monitoring.css">

            <title id="page-title"><?= $lang['page_title_AQI'] ?></title>

    </head>
    <main class="monitoring-container">
        <section class="filter-section">
            <div>
                <div style="display: flex; justify-content: space-between;">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        <?= $lang['page_title_AQI'] ?>
                    </h2>
                </div>
            </div>
            <div class="filter-card">
                <div class="filter-content">
                    <!-- Station Selector -->
                    <div class="filter-row">
                        <div class="filter-group full-width">
                            <label for="tag">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= $lang['label_station'] ?>:
                            </label>
                            <select id="tag" class="custom-select" onchange="gerarGraficoAQI()">
                                <option value=""><?= $lang['loading_stations'] ?></option>
                            </select>
                        </div>
                        <div class="filter-group full-width">
                            <label for="element">
                                <i class="fas fa-flask"></i>
                                <?= $lang['label_element'] ?>:
                            </label>
                            <select id="element" class="custom-select" onchange="gerarGraficoAQI()">
                                <option value=""><?= $lang['select_element'] ?></option>
                                <option value="iqa_final"><?= $lang['element_iqa_final'] ?></option>
                                <option value="iqa_pm10"><?= $lang['element_iqa_pm10'] ?></option>
                                <option value="iqa_pm25"><?= $lang['element_iqa_pm25'] ?></option>
                                <option value="iqa_o3"><?= $lang['element_iqa_o3'] ?></option>
                                <option value="iqa_co"><?= $lang['element_iqa_co'] ?></option>
                                <option value="iqa_no2"><?= $lang['element_iqa_no2'] ?></option>
                                <option value="iqa_so2"><?= $lang['element_iqa_so2'] ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Selector -->
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="startDate">
                                <i class="far fa-calendar-alt"></i>
                                <?= $lang['label_start_date'] ?>:
                            </label>
                            <input type="datetime-local" id="startDate" class="custom-input">
                        </div>

                        <div class="filter-group">
                            <label for="endDate">
                                <i class="far fa-calendar-alt"></i>
                                <?= $lang['label_end_date'] ?>:
                            </label>
                            <input type="datetime-local" id="endDate" class="custom-input">
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="filter-action">
                        <button class="filter-btn" onclick="gerarGraficoAQI()">
                            <i class="fas fa-filter"></i>
                            <?= $lang['btn_filter_data'] ?>
                        </button>
                    </div>
                </div>
            </div>
        </section>


        <!-- Chart Container -->
        <section class="chart-section">
            <div id="chart" class="chart-container"></div>
        </section>
    </main>
    <!-- Rodapé -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Acoem. <?= $lang['footer_rights'] ?></p>
            <div class="contact-container">
                <div class="email-contact">
                    <i class="fas fa-envelope pulse"></i>
                    <a href="mailto:smamus@portoalegre.rs.gov.br">smamus@portoalegre.rs.gov.br</a>
                </div>
            </div>
        </div>
        <div id="FooterSeals">
            <a href="https://www.acoem.com/brasil/pt-br/" target="_blank" rel="noopener noreferrer">
                <img src="../src/images/logo-acoem.svg" alt="Logo Acoem" style="margin: 20px;" />
            </a>
            <a href="https://prefeitura.poa.br">
                <img src="../src/images/prefeitura Porto Alegre secre.png" alt="<?= $lang['footer_city_hall'] ?>"
                    style="width: 300px;height: 150px;">
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
    <script src="../index.js"></script>
</body>

</html>