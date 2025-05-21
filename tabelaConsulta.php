<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include 'phpconfig.php';
include 'head.php';
include 'header.php';
include 'tabelaDados.php';
include 'gerartabela.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title id="page-title">Usuarios</title>

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
</head>

<body>
    <main class="monitoring-container">
        <section class="filter-section">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-chart-line"></i> Dados por Estação e Data
                </h2>

                <label for="stationSelect">Estação:</label>
                <select id="stationSelect">
                    <option value="">Selecione...</option>
                    <option value="station1">São Carlos</option>
                    <option value="station2">Restinga</option>
                    <option value="station3">AMRIGS</option>
                    <option value="station4">Unidade Móvel</option>
                    <option value="station5">Moacyr Scliar</option>
                    <option value="station6">Rodoviária</option>
                </select>

                <label for="startDate">Início:</label>
                <input type="date" id="startDate" />

                <label for="endDate">Fim:</label>
                <input type="date" id="endDate" />

                <button id="filtrarBtn">Filtrar</button>
            </div>

            <div class="mt-5">
                <div class="table-responsive shadow rounded-3">
                    <table class="table table-hover table-bordered align-middle text-center bg-white">
                        <thead class="table-dark">
                            <tr>
                                <th>TimeStamp</th>
                                <th>Tag</th>
                                <th>temp</th>
                                <th>umid</th>
                                <th>press</th>
                                <th>vel</th>
                                <th>chuva</th>
                                <th>rad</th>
                                <th>dir</th>
                            </tr>
                        </thead>
                        <tbody id="dadosTabela">
                            <tr><td colspan="9">Selecione uma estação e intervalo de datas.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer>
        
    </footer>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="index.js"></script>
    <script src="assets/js/botaoIdioma.js"></script>
    <script src="tabela.js"></script>
    <script src="requests.js" type="module"></script>
    <script src="assets/js/chartsTest.js"></script>
    <script src="assets/js/baixarDados.js"></script>
</body>
</html>
