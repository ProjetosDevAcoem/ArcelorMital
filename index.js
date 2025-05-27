// Inicializa o mapa
const map = L.map('map').setView([-30.114092, -51.142017], 12);

const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Função para buscar os dados das estações
async function fetchStationData(timestamp) {
    let url = "back/file.php"
    if (timestamp != null) {
        url = `back/file?timestamp=${timestamp}`; // URL do seu arquivo PHP que retorna os dados das estações
    }

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Erro HTTP! Status: ${response.status}`);
        }
        const data = await response.json(); // Parseia os dados JSON retornados
        //console.log(data); // Loga os dados no console para conferência
        return data; // Retorna os dados para processar os marcadores
    } catch (error) {
        console.error("Erro ao obter dados:", error);
        return [];
    }
}


function mudarNome(tagname) {
    const nomeDaEstacao = document.getElementById("info__title"); // Adicionado aspas na ID
    if (nomeDaEstacao) {
        nomeDaEstacao.innerHTML = `${tagname}`; // Corrigida a atribuição do innerHTML
    } else {
        console.error("Elemento com ID 'info__title' não encontrado.");
    }
}


// Função para processar os dados das estações e adicionar os marcadores
function processStationData(station) {
    const latitude = station.Latitude;
    const longitude = station.Longitude;
    const nomeDatabela = station.TableName;
    const tag = station.Tag;

    let aqiDetails = { color: 'gray', classification: 'N/A', icon: "/src/images/icone acoem desativado A2.svg", Desc: 'No data available' };

    const marker = CreateMarker(latitude, longitude, aqiDetails.icon);
    //Aproxima a tela ao clicar.
    marker.on('click', function () {
        map.setView([latitude, longitude], 16);
        // Carrega dados na tabela do lado de fora do grafico
        exibirDadosEstacao(nomeDatabela);
    })


    markers.push(marker); // Adiciona o marcador ao array de marcadores
    marker.addTo(map);

    // Função para obter cor do AQI
    // function getAQIColor(parametro, valor) {
    //     // Aqui é onde você define a lógica de cores com base no valor do AQI
    //     if (parametro && parametro.id === "AQI") { // Verifica se o id do parametro é "AQI"
    //         if (valor <= 40) return "#00e400"; // bom
    //         if (valor <= 80) return "#f7d400"; // Moderado
    //         if (valor <= 120) return "#ff7e00"; // Ruim
    //         if (valor <= 200) return "#ff0000"; // Muito Ruim
    //         if (valor <= 300) return "#950e61"; // Péssima
    //     }
    //     return "grey"; // Caso o parâmetro não seja o esperado
    // }

    const popupContent = `
        <div style="text-align: center;">
            <p>Name: ${tag}</p>
        </div>
    `;
    marker.bindTooltip(popupContent, { direction: "top", permanent: false });

    marker.on('mouseover', function () {
        this.openTooltip();
    });

    marker.on('mouseout', function () {
        this.closeTooltip();
    });

    //marker.on('click', () => {
    //    const selectelement = document.getElementById('select');
    //    const tagelement = document.getElementById('StationTag');
    //    const timestamp = document.getElementById('LastRead');
    //    const aqiElement = document.getElementById('AQI');
    //    selectelement.style.display = 'none';
    //    aqiElement.innerText = `AQI: ${IQA}`;
    //    timestamp.innerText = `Última leitura: ${aqiTimeStamp}`;
    //    tagelement.innerText = `Nome: ${tag}`;
    //});
}

// Dados da estação que serão exibidos no layout principal
function carregarDadosEstacoes() {
    fetch("back/file.php") // Arquivo PHP que retorna o JSON
        .then(response => response.json())
        .then(dados => {
            estacoes = dados; // Salva os dados para uso posterior

        })
        .catch(error => console.error("Erro ao buscar dados das estações:", error));
}


// Função para exibir os dados na tela ao clicar no mapa
function exibirDadosEstacao(TableName) {
    const estacao = estacoes.find(est => est.TableName === TableName);

    // Opções corretas para formatação de data
    const opcoes = { 
        day: 'numeric', 
        month: 'long', 
        hour: '2-digit', 
        minute: '2-digit', 
        year: 'numeric', 
        timeZone: 'America/Sao_Paulo' 
    };

    let dataFormatada = langStatus.date_unavailable;
    if (estacao.TimeStamp) {
        let timestamp = Number(estacao.TimeStamp);
        if (timestamp < 1e12) timestamp *= 1000;

        const locale = langMap[currentLang] || 'pt-BR'; // fallback em caso de erro
        dataFormatada = new Date(timestamp).toLocaleDateString(locale, opcoes);
    }

    console.log(dataFormatada);

    if (estacao) {
        const temp = `<p>${estacao.temp}</p>`;
        const umid = `<p>${estacao.umid}</p>`;
        const vel = `<p>${estacao.vel}</p>`;
        const pressure = `<p>${estacao.pressure}</p>`;
        const radiation = `<p>${estacao.radiation}</p>`;
        const rain = `<p>${estacao.rain}</p>`;
        const dir = `<p>${estacao.dir}</p>`;

        document.getElementById("date").innerHTML = date;
        document.getElementById("temperature").innerHTML = temp;
        document.getElementById("humidity").innerHTML = umid;
        document.getElementById("wind-speed").innerHTML = vel;
        document.getElementById("pressure").innerHTML = pressure;
        document.getElementById("radiation").innerHTML = radiation;
        document.getElementById("rain").innerHTML = rain;
        document.getElementById("windDirection").innerHTML = dir;
    }
}


// Chama a função ao carregar a página
document.addEventListener("DOMContentLoaded", carregarDadosEstacoes);


// Função para criar o marcador no mapa
function CreateMarker(latitude, longitude, icon) {
    const KunakIcon = L.icon({
        iconUrl: icon,
        iconSize: [100, 100],
        iconAnchor: [24, 24],
        popupAnchor: [0, -16]
    });

    return L.marker([latitude, longitude], { icon: KunakIcon });
}
let markers = [];

// Função para remover todos os marcadores
function removeMarkers() {
    markers.forEach(marker => {
        map.removeLayer(marker); // Remove o marcador do mapa
    });
    markers = []; // Limpa o array de marcadores
}

// Função para formatar a data do TimeStamp
function formatTimestamp(timestamp) {
    if (!timestamp) return "N/A";
    const date = new Date(timestamp);
    if (isNaN(date.getTime())) return "N/A"; // Verifica se é uma data válida
    return date.toLocaleString(); // Formata para data e hora local
}

// Função para classificar o valor do AQI e retornar o ícone apropriado
function getAQIClassification(aqi) {
    if (aqi <= 40) {
        return {
            color: '#00e400',
            classification: langStatus.status_good,
            icon: "src/images/icone acoem boa A2.svg",
            Desc: langStatus.desc_good
        };
    } else if (aqi <= 80) {
        return {
            color: '#f7d400',
            classification: langStatus.status_moderate,
            icon: "/src/images/icone acoem moderada A3.svg",
            Desc: langStatus.desc_moderate
        };
    } else if (aqi <= 120) {
        return {
            color: '#ff7e00',
            classification: langStatus.status_bad,
            icon: "/src/images/icone acoem ruim A2.svg",
            Desc: langStatus.desc_bad
        };
    } else if (aqi <= 200) {
        return {
            color: '#ff0000',
            classification: langStatus.status_very_bad,
            icon: "/src/images/icone acoem muito ruim A2.svg",
            Desc: langStatus.desc_very_bad
        };
    } else if (aqi <= 300) {
        return {
            color: '#950e61',
            classification: langStatus.status_terrible,
            icon: "/src/images/icone acoem péssima A2.svg",
            Desc: langStatus.desc_terrible
        };
    } else {
        return {
            color: 'grey',
            classification: langStatus.status_inactive,
            icon: "/src/images/icone acoem desativado A2.svg",
            Desc: langStatus.desc_inactive
        };
    }
}

// Função para exibir a classificação no HTML
function updateStatus(aqi) {
    const classificacao = getAQIClassification(aqi).classification;
    // Atualiza o conteúdo do elemento com id="status"
    document.getElementById("status").innerHTML = `${classificacao}`;
}

// Função principal para mostrar todas as estações no mapa
async function ShowStationsOnMap() {
    removeMarkers(); // Remove os marcadores existentes
    const stationData = await fetchStationData(); // Obtém os dados das estações via PHP
    stationData.forEach(station => {
        processStationData(station); // Processa e adiciona os marcadores no mapa
    });
}

// Inicia o processo de exibição
ShowStationsOnMap();
// Obtendo o conteúdo do iframe
// Acessando o iframe e seu conteúdo
// Obtendo o iframe

// Mobile touch handling
document.addEventListener('DOMContentLoaded', function () {
    // Only add touch handlers on mobile devices
    if (window.matchMedia('(max-width: 768px)').matches) {
        const cards = document.querySelectorAll('.elemento-card');

        cards.forEach(card => {
            // Remove onclick attribute if it exists
            card.removeAttribute('onclick');

            // Add touch handler
            card.addEventListener('touchstart', function () {
                // Reset all other cards
                cards.forEach(c => {
                    if (c !== card) {
                        c.classList.remove('touch-flip');
                    }
                });

                // Toggle this card
                this.classList.toggle('touch-flip');
            });
        });
    }
});