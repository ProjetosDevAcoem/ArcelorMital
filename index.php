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
    <main class="body__main" style="background-color: white;">
        <div class="">
            <div class="row">
                <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <svg class="bi me-2" width="40" height="32">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                        <span class="fs-4">Estações</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" aria-current="page">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg>
                                Home
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>mdo</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Coluna do mapa -->
                <div class="col-md-8 map" style="padding: 0;">
                    <div id="map"></div>
                </div>

                <!-- Coluna dos ícones meteorológicos -->
                <div class="col-md-2">
                    <style>
                        .stationHeader {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            background: linear-gradient(145deg, #ffffff, #f5f7fa);
                            border-radius: 15px;
                            text-align: center;
                            box-shadow: 0 8px 20px rgba(45, 62, 93, 0.08);
                            transition: transform 0.3s ease;
                            border: 1px solid rgba(255, 255, 255, 0.8);
                            margin-bottom: 10px;
                        }

                        section.stationHeader:hover {
                            transform: translateY(-5px);
                        }

                        .meteo-value {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }
                    </style>
                    <section class="stationHeader">
                        <h2 id="StationTitle"></h2>
                        <span id="date" class="date"></span>
                    </section>
                    <section class="meteo-grid">
                        <?php
                        $cards = [
                            ['title' => $lang['temperature'], 'id' => 'temperature', 'unit' => '°C'],
                            ['title' => $lang['humidity'], 'id' => 'humidity', 'unit' => '%'],
                            ['title' => $lang['wind_speed'], 'id' => 'wind-speed', 'unit' => 'km/h'],
                            ['title' => $lang['pressure'], 'id' => 'pressure', 'unit' => 'hPa'],
                            ['title' => $lang['solar_radiation'], 'id' => 'radiation', 'unit' => 'W/m²'],
                            ['title' => $lang['rain'], 'id' => 'rain', 'unit' => 'mm/h'],
                            ['title' => $lang['wind_direction'], 'id' => 'windDirection', 'unit' => '°']
                        ];
                        foreach ($cards as $card) :
                        ?>
                            <div class="meteo-card">
                                <div class="meteo-icon"></div>
                                <h3><?= $card['title'] ?></h3>
                                <div class="meteo-value">
                                    <span id="<?= $card['id'] ?>" class="value"></span><span class="unit"><?= $card['unit'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>
                </div>
            </div>
            <script>
                const currentLang = "<?= $_SESSION['lang'] ?>";
            </script>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Acoem. <?= $lang['footer_rights'] ?></p>
            <div class="contact-container"></div>
        </div>
        <div id="FooterSeals">
            <a href="https://www.acoem.com/brasil/pt-br/" target="_blank" rel="noopener noreferrer">
                <img src="../src/images/logo-acoem.svg" alt="Logo Acoem" style="margin: 20px;" />
            </a>
            <div class="secure-seal">
                <span id="ss_img_wrapper_115-55_image_en">
                    <a href="http://www.alphassl.com/ssl-certificates/wildcard-ssl.html" target="_blank" title="SSL Certificates">
                        <img alt="<?= $lang['footer_ssl_alt'] ?>" id="ss_img" src="//seal.alphassl.com/SiteSeal/images/alpha_noscript_115-55_en.gif" title="SSL Certificate">
                    </a>
                </span>
                <script type="text/javascript" src="//seal.alphassl.com/SiteSeal/alpha_image_115-55_en.js"></script>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="../assets/js/index.js"></script>
</body>

</html>
