<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include '../lang/langConfig.php';
include 'head.php';
include 'header.php';
?>

<!-- graficos -->
<?php include 'graficos/press.php'; ?>
<?php include 'graficos/chuva.php'; ?>
<?php include 'graficos/rad.php'; ?>
<?php include 'graficos/temp.php'; ?>
<?php include 'graficos/umid.php'; ?>
<?php include 'graficos/vel.php'; ?>
<?php include 'graficos/dir.php'; ?>
<!-- graficos fim -->

<!DOCTYPE html>
<html lang="en">

<head>
    <title id="page-title"><?= $lang['page_title_met'] ?></title>
</head>

<body>
    <main class="meteo-container">
        <div class="station-selector animate__animated animate__fadeIn">
            <h2 class="selector-title">
                <i class="fas fa-map-marked-alt"></i>
                <?= $lang['station_selector_title'] ?>
            </h2>
            <div class="station-buttons">
                <button class="station-btn active" data-station="station1">
                    <div class="station-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="station-info">
                        <span class="station-name">US São Carlos</span>
                        <span class="station-address">Av. Bento Gonçalves, 6670 </span>
                        <!--<span class="station-status online">Online</span>-->
                    </div>
                </button>
                <button class="station-btn" data-station="station2">
                    <div class="station-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="station-info">
                        <span class="station-name">Hospital da Restinga</span>
                        <span class="station-address">Estrada João Antônio da Silveira, 3700</span>
                        <!--<span class="station-status online">Online</span>-->
                    </div>
                </button>
                <button class="station-btn" data-station="station3">
                    <div class="station-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="station-info">
                        <span class="station-name">AMRIGS</span>
                        <span class="station-address">Av. Ipiranga, 5311</span>
                        <!--<span class="station-status online">Online</span>-->
                    </div>
                </button>
                <button class="station-btn" data-station="station4">
                    <div class="station-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="station-info">
                        <span class="station-name">Unidade Móvel</span>
                        <span class="station-address">Localização Variável</span>
                        <!--<span class="station-status online">Online</span>-->
                    </div>
                </button>
                <button class="station-btn" data-station="station5">
                    <div class="station-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="station-info">
                        <span class="station-name">UPA Moacyr Scliar</span>
                        <span class="station-address">R. Jerônymo Zelmanovitz, 01</span>
                        <!--<span class="station-status online">Online</span>-->
                    </div>
                </button>
                <button class="station-btn" data-station="station6">
                    <div class="station-icon">
                        <i class="fas fa-bus"></i>
                    </div>
                    <div class="station-info">
                        <span class="station-name">Estação Rodoviária</span>
                        <span class="station-address">Largo Vespasiano Júlio Veppo, 70</span>
                        <!--<span class="station-status online">Online</span>-->
                    </div>
                </button>
            </div>
        </div>

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

        <section>
            <div class="filter-card">
                <div class="filter-content">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="startDate">
                                <i class="far fa-calendar-alt"></i>
                                <?= $lang['start_date'] ?>
                            </label>
                            <input type="datetime-local" id="startDate" class="custom-input">
                        </div>
                        <div class="filter-group">
                            <label for="endDate">
                                <i class="far fa-calendar-alt"></i>
                                <?= $lang['end_date'] ?>
                            </label>
                            <input type="datetime-local" id="endDate" class="custom-input">
                        </div>
                    </div>
                    <div class="filter-action">
                        <button class="filter-btn" onclick="gerarGraficoAQI()">
                            <i class="fas fa-filter"></i>
                            <?= $lang['generate_charts'] ?>
                        </button>
                    </div>
                </div>
            </div>
            <div id="vel">

            </div>
            <div id="dir">

            </div>
            <div id="rad">

            </div>
            <div id="press">

            </div>
            <div id="temp">

            </div>
            <div id="umid">

            </div>
            <div id="chuva">

            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>© 2025 Acoem. <?= $lang['all_rights_reserved'] ?></p>
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
                <img src="src/images/prefeitura Porto Alegre secre.png" alt="Prefeitura de Porto Alegre"
                    style="width: 300px;height: 150px;">
            </a>
    
            <div class="secure-seal">
                <span id="ss_img_wrapper_115-55_image_en">
                    <a href="http://www.alphassl.com/ssl-certificates/wildcard-ssl.html" target="_blank"
                        title="SSL Certificates">
                        <img alt="Wildcard SSL Certificates" id="ss_img"
                            src="//seal.alphassl.com/SiteSeal/images/alpha_noscript_115-55_en.gif"
                            title="SSL Certificate">
                    </a>
                </span>
            </div>
        </div>
    </footer>
    <script type="text/javascript" src="//seal.alphassl.com/SiteSeal/alpha_image_115-55_en.js"></script>
    <script src="../assets/js/meteo.js"></script>
</body>
</html>