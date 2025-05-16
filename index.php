<?php
include 'phpconfig.php';
include 'head.php';
include 'header.php';
include 'graficos/carregarpaginainicial.php';
?>

<head>
    <title id="page-title"><?= $lang['page_title_index'] ?></title>
</head>
<body>
    <!-- Main body -->
    <main class="body__main">
        <div class="container">
            <div id="center">
                <div id="map">
                    <!-- Legenda -->
                    <div class="legenda-qualidade-ar">
                        <img src="<?= $lang['air_quality_legend_url'] ?>" alt="<?= $lang['air_quality_legend'] ?>">
                    </div>
                </div>
            </div>

            <div id="info" class="info">
                <div class="info__tabela">
                    <h2 id="title-kunak-station" class="station-title"></h2>
                    <div class="aqi-box aqi-box-elements">
                        <div class="aqi-box-elements">
                            <div class="aqi-details">
                                <div class="detail-item">
                                    <span id="AQI" class="aqi-value"></span>
                                </div>
                            </div>
                            <!-- Chamada dos status em idiomas diferentes -->
                            <script>
                                const langStatus = <?= json_encode([
                                    'status_good' => $lang['status_good'],
                                    'status_moderate' => $lang['status_moderate'],
                                    'status_bad' => $lang['status_bad'],
                                    'status_very_bad' => $lang['status_very_bad'],
                                    'status_terrible' => $lang['status_terrible'],
                                    'status_inactive' => $lang['status_inactive'],
                                    'date_unavailable' => $lang['date_unavailable']
                                ], JSON_UNESCAPED_UNICODE) ?>;
                            </script>
                            <h3 id="status" class="status-text"></h3>
                            <!-- Fim de chamada -->

                            <!-- Chamada de data em idiomas diferentes -->
                            <script>
                                const currentLang = "<?= $_SESSION['lang'] ?>";
                            </script>
                            <span id="date" class="date"></span>
                            <!-- Fim de chamada -->
                        </div>
                    </div>
                </div>

                <div class="elementosAQI">
                    <!-- Cards dos Poluentes -->
                    <?php
                    $poluentes = [
                        'pm25' => ['PM2.5', 'pm25', 'fine_particulate'],
                        'pm10' => ['PM10', 'pm10', 'inhalable_particles'],
                        'o3'   => ['O₃', 'o3', 'ozone'],
                        'no2'  => ['NO₂', 'no2', 'nitrogen_dioxide'],
                        'so2'  => ['SO₂', 'so2', 'sulfur_dioxide'],
                        'co'   => ['CO', 'co', 'carbon_monoxide'],
                    ];

                    foreach ($poluentes as $id => [$label, $elementId, $descKey]) {
                        echo <<<HTML
                        <div class="elemento-card" onclick="flipCard(this)">
                            <div class="flip-container">
                                <div class="front">
                                    <div class="titulo-container">
                                        <h1>{$lang['iqar']}<br>{$label}</h1>
                                        <h2 class="elemento-valor" id="{$elementId}">--</h2>
                                    </div>
                                </div>
                                <div class="back">
                                    <div class="back-content">
                                        <h2>{$lang['air_quality_index']}</h2>
                                        <p>{$lang[$descKey]}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        HTML;
                    }
                    ?>
                </div>
            </div>
        </div>
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
                <img src="src/images/logo-acoem.svg" alt="Logo Acoem" style="margin: 20px;" />
            </a>
            <a href="https://prefeitura.poa.br">
                <img src="src/images/prefeitura Porto Alegre secre.png" alt="<?= $lang['footer_city_hall'] ?>"
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
    <script src="index.js"></script>
    <script src="assets/js/botaoIdioma.js"></script>
</body>
</html>
