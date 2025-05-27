<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include 'partials/head.php';
include 'partials/header.php';
include 'graficos/carregarpaginainicial.php';
?>

<head>
    <title id="page-title"><?= $lang['page_title_index'] ?></title>
</head>

<body>
    <!-- Main body -->
    <main class="body__main">
        <div class="">
            <div class="row">
                <!-- Coluna do mapa -->
                <div class="col-md-8" id="">
                    <div id="map">
                        <!-- Legenda -->
                        <div class="legenda-qualidade-ar" style="position: absolute; bottom: 10px; left: 10px; z-index: 1000;">
                            <img src="<?= $lang['air_quality_legend_url'] ?>" alt="<?= $lang['air_quality_legend'] ?>">
                        </div>
                    </div>
                </div>

                <!-- Coluna dos ícones meteorológicos -->
                <div class="col-md-4">
                    <section class="meteo-grid">
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-temperature-high"></i></div>
                            <h3><?= $lang['temperature'] ?></h3>
                            <div class="meteo-value" id="temperature">
                                <span class="value"></span><span class="unit">°C</span>
                            </div>
                        </div>
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-tint"></i></div>
                            <h3><?= $lang['humidity'] ?></h3>
                            <div class="meteo-value" id="humidity">
                                <span class="value"></span><span class="unit">%</span>
                            </div>
                        </div>
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-wind"></i></div>
                            <h3><?= $lang['wind_speed'] ?></h3>
                            <div class="meteo-value" id="wind-speed">
                                <span class="value"></span><span class="unit">km/h</span>
                            </div>
                        </div>
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-compress-alt"></i></div>
                            <h3><?= $lang['pressure'] ?></h3>
                            <div class="meteo-value" id="pressure">
                                <span class="value"></span><span class="unit">hPa</span>
                            </div>
                        </div>
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-sun"></i></div>
                            <h3><?= $lang['solar_radiation'] ?></h3>
                            <div class="meteo-value" id="radiation">
                                <span class="value"></span><span class="unit">W/m²</span>
                            </div>
                        </div>
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-cloud-rain"></i></div>
                            <h3><?= $lang['rain'] ?></h3>
                            <div class="meteo-value" id="rain">
                                <span class="value"></span><span class="unit">mm/h</span>
                            </div>
                        </div>
                        <div class="meteo-card">
                            <div class="meteo-icon"><i class="fas fa-location-arrow wind-direction-icon"></i></div>
                            <h3><?= $lang['wind_direction'] ?></h3>
                            <div class="meteo-value" id="windDirection">
                                <span class="value"></span><span class="unit">°</span>
                            </div>
                        </div>
                    </section>
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

            <!-- Chamada de data em idiomas diferentes -->
            <script>
                const currentLang = "<?= $_SESSION['lang'] ?>";
            </script>
            <span id="date" class="date"></span>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Acoem. <?= $lang['footer_rights'] ?></p>
            <div class="contact-container">
            </div>
        </div>
        <div id="FooterSeals">
            <a href="https://www.acoem.com/brasil/pt-br/" target="_blank" rel="noopener noreferrer">
                <img src="../src/images/logo-acoem.svg" alt="Logo Acoem" style="margin: 20px;" />
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
    <script src="/assets/js/botaoIdioma.js"></script>
</body>

</html>
