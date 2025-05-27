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

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">

<head>
    <meta charset="UTF-8">
    <title id="page-title"><?= $lang['page_title_learn_more'] ?></title>
    <link rel="stylesheet" href="../assets/css/saibamais.css">
    <link rel="stylesheet" href="../assets/css/saibamais-content.css">
</head>

<body>
    <main class="saibamais-container">
        <!-- Poluentes Monitorados Section -->
        <section class="info-section poluentes">
            <h2><?= $lang['monitored_pollutants'] ?></h2>
            <table class="standards-table">
                <thead>
                    <tr>
                        <th><?= $lang['pollutant'] ?></th>
                        <th><?= $lang['symbol'] ?></th>
                        <th><?= $lang['characteristics'] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $lang['fine_particles'] ?></td>
                        <td><?= $lang['symbol_pm25'] ?></td>
                        <td><?= $lang['fine_particles_desc'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $lang['inhalable_particles'] ?></td>
                        <td><?= $lang['symbol_pm10'] ?></td>
                        <td><?= $lang['inhalable_particles_desc'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $lang['ozone'] ?></td>
                        <td><?= $lang['symbol_o3'] ?></td>
                        <td><?= $lang['ozone_desc'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $lang['nitrogen_dioxide'] ?></td>
                        <td><?= $lang['symbol_no2'] ?></td>
                        <td><?= $lang['nitrogen_dioxide_desc'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $lang['sulfur_dioxide'] ?></td>
                        <td><?= $lang['symbol_so2'] ?></td>
                        <td><?= $lang['sulfur_dioxide_desc'] ?></td>
                    </tr>
                    <tr>
                        <td><?= $lang['carbon_monoxide'] ?></td>
                        <td><?= $lang['symbol_co'] ?></td>
                        <td><?= $lang['carbon_monoxide_desc'] ?></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Índice de Qualidade do Ar Section -->
        <section class="info-section qualidade-ar">
            <h2><?= $lang['air_quality_index'] ?></h2>
            <div class="quality-levels">
                <div class="quality-card quality-boa">
                    <h3><?= $lang['level_1'] ?></h3>
                </div>
                <div class="quality-card quality-moderada">
                    <h3><?= $lang['level_2'] ?></h3>
                </div>
                <div class="quality-card quality-ruim">
                    <h3><?= $lang['level_3'] ?></h3>
                </div>
                <div class="quality-card quality-muito-ruim">
                    <h3><?= $lang['level_4'] ?></h3>
                </div>
                <div class="quality-card quality-pessima">
                    <h3><?= $lang['level_5'] ?></h3>
                </div>
            </div>
        </section>

        <!-- Marcos Importantes Section -->
        <section class="info-section marcos">
            <h2><?= $lang['air_quality_standards_milestones'] ?></h2>
            <ul class="timeline">
                <li>
                    <a href="https://conama.mma.gov.br/index.php?option=com_sisconama&task=arquivo.download&id=827"
                        target="_blank" class="timeline-link">
                        <?= $lang['conama_506_2024'] ?>
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </li>
            </ul>
        </section>
    </main>

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
                        <img alt="Wildcard SSL Certificates" id="ss_img"
                            src="//seal.alphassl.com/SiteSeal/images/alpha_noscript_115-55_en.gif"
                            title="SSL Certificate">
                    </a>
                </span>
                <script type="text/javascript" src="//seal.alphassl.com/SiteSeal/alpha_image_115-55_en.js"></script>
            </div>
        </div>
    </footer>
</body>

</html>
