// Função para carregar dados ao clicar em uma estação
let estacoes = []; // Array para armazenar os dados das estações

// Função para carregar os dados do JSON
function carregarDadosEstacoes() {
    fetch("file.php") // Arquivo PHP que retorna o JSON
        .then(response => response.json())
        .then(dados => {
            estacoes = dados; // Salva os dados para uso posterior
            adicionarPontosNoMapa(); // Chama a função para adicionar os pontos no mapa
        })
        .catch(error => console.error("Erro ao buscar dados das estações:", error));
}

// Função para exibir os dados na tela ao clicar no mapa
function exibirDadosEstacao(serialNumber) {
    const estacao = estacoes.find(est => est.SerialNumber === serialNumber);
    
    if (estacao) {
        const info = `
            <h3>${estacao.Tag}</h3>
            <p><strong>Latitude:</strong> ${estacao.Latitude}</p>
            <p><strong>Longitude:</strong> ${estacao.Longitude}</p>
            <p><strong>AQI:</strong> ${estacao.AQI}</p>
            <p><strong>PM10 (1H AVG):</strong> ${estacao.PM10AVG1H}</p>
            <p><strong>PM2.5 (1H AVG):</strong> ${estacao.PM25AVG1H}</p>
            <p><strong>O3 (1H AVG):</strong> ${estacao.O3GCcAVG1H}</p>
            <p><strong>CO (1H AVG):</strong> ${estacao.COGCcAVG1H}</p>
        `;

        document.getElementById("info-estacao").innerHTML = info; // Exibe os dados na div
    }
}

// Função para adicionar os pontos das estações no mapa
function adicionarPontosNoMapa() {
    estacoes.forEach(estacao => {
        // Supondo que você esteja usando Leaflet.js
        L.marker([estacao.Latitude, estacao.Longitude])
            .addTo(map) // 'mymap' é a variável do mapa Leaflet
            .on("click", () => exibirDadosEstacao(estacao.SerialNumber)); // Evento de clique
    });
}

// Chama a função ao carregar a página
document.addEventListener("DOMContentLoaded", carregarDadosEstacoes());