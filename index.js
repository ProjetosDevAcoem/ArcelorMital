// Inicializa o mapa
const map = L.map('map').setView([-30.114092, -51.142017], 12);

const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Função para buscar os dados das estações
async function fetchStationData(timestamp) {
    let url = "file.php"
    if (timestamp != null) {
        url = `file.php?timestamp=${timestamp}`; // URL do seu arquivo PHP que retorna os dados das estações
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
    const timestamp = station.TimeStamp;
    const latitude = station.Latitude;
    const longitude = station.Longitude;
    const tag = station.Tag;
    const IQA = station.iqa_final;
    const SerialN = station.SerialNumber.replace('S_', "");
    const aqiValue = station.iqa_final || 'N/A';
    const aqiTimeStamp = formatTimestamp(station.TimeStamp);

    let aqiDetails = { color: 'gray', classification: 'N/A', icon: "/src/images/icone acoem desativado A2.svg", Desc: 'No data available' };
    if (typeof IQA === 'number') {
        aqiDetails = getAQIClassification(IQA);
    }

    const marker = CreateMarker(latitude, longitude, aqiDetails.icon);
    //Aproxima a tela ao clicar.
    marker.on('click', function () {
        map.setView([latitude, longitude], 16);
        // Carrega dados na tabela do lado de fora do grafico
        atualizarEstacao(tag);
        exibirDadosEstacao(station.SerialNumber);
        updateStatus(IQA);
    })


    markers.push(marker); // Adiciona o marcador ao array de marcadores
    marker.addTo(map);


    const estacoes = [
        "S_4223410062", "S_4223410063", "S_4223410064", "S_4223410065",
        "S_4223410066", "S_4223410067", "S_4223410068", "S_4223410069",
        "S_4223410070", "S_4223410071"
    ];

    function atualizarEstacao(estacao) {
        const parametros = document.getElementById('AQI');
        const nametag = document.getElementById("info__title");

        // Certificando-se de que o 'parametros' é válido antes de tentar alterar o estilo
        if (parametros) {
            parametros.style.backgroundColor = "white";
        }

        // Buscar dados do banco
        fetch(`file.php?estacao=${estacao}`)
            .then(response => response.json())
            .then(dados => {
                console.log(`Resposta da API para ${estacao}:`, dados);

                if (Array.isArray(dados) && dados.length > 0) {
                    if (dados[0].tag_estacao && nametag) {
                        nametag.innerHTML = `Estação: ${dados[0].tag_estacao}`;
                    }

                    // Supondo que 'dados[0].aqi' seja o valor que você quer verificar 
                    const color = getAQIColor(parametros, aqiValue);

                    // Verificando se 'parametros' é válido para alterar o estilo
                    if (parametros) {
                        parametros.style.backgroundColor = color;
                    }

                } else {
                    console.error(`Erro: Dados inválidos recebidos da API para ${estacao}`);
                }
            })
            .catch(error => console.error(`Erro ao buscar dados da API para ${estacao}:`, error));
    }

    // Função para obter cor do AQI
    function getAQIColor(parametro, valor) {
        // Aqui é onde você define a lógica de cores com base no valor do AQI
        if (parametro && parametro.id === "AQI") { // Verifica se o id do parametro é "AQI"
            if (valor <= 40) return "#00e400"; // bom
            if (valor <= 80) return "#f7d400"; // Moderado
            if (valor <= 120) return "#ff7e00"; // Ruim
            if (valor <= 200) return "#ff0000"; // Muito Ruim
            if (valor <= 300) return "#950e61"; // Péssima
        }
        return "grey"; // Caso o parâmetro não seja o esperado
    }

    const popupContent = `
        <div style="text-align: center;">
            <p>Name: ${tag}</p>
            <p>IQA: ${IQA}</p>
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
        const titleKunakStation = `<h2>${estacao.Tag}</h2>`;
        const date = `<p>${dataFormatada}</p>`;
        const aqi = `${estacao.iqa_final}`;
        const pm10 = `${estacao.iqa_pm10}`;
        const pm25 = `${estacao.iqa_pm25}`;
        const o3 = `${estacao.iqa_o3}`;
        const co = `${estacao.iqa_co}`;
        const so2 = `${estacao.iqa_so2}`;
        const no2 = `${estacao.iqa_no2}`;

        document.getElementById("title-kunak-station").innerHTML = titleKunakStation;
        document.getElementById("date").innerHTML = date;
        document.getElementById("AQI").innerHTML = aqi;
        document.getElementById("pm10").innerHTML = pm10;
        document.getElementById("pm25").innerHTML = pm25;
        document.getElementById("o3").innerHTML = o3;
        document.getElementById("co").innerHTML = co;
        document.getElementById("so2").innerHTML = so2;
        document.getElementById("no2").innerHTML = no2;
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



class CustomCalendar {
    constructor(containerId, hoursContainerId, timestamps) {
        this.container = document.getElementById(containerId);
        this.hoursContainer = document.getElementById(hoursContainerId);
        this.availableDates = this.processTimestamps(timestamps);
        this.availableYears = this.extractAvailableYearsAndMonths(timestamps);

        // Definir data e hora padrão como última disponível
        let lastDate = Object.keys(this.availableDates).sort().pop();
        this.selectedDate = lastDate;
        this.selectedHour = lastDate ? Math.max(...this.availableDates[lastDate]) : null;

        this.currentYear = lastDate ? new Date(lastDate).getFullYear() : new Date().getFullYear();
        this.currentMonth = lastDate ? new Date(lastDate).getMonth() : 0;

        this.renderCalendar();
    }
    processTimestamps(timestamps) {
        let dateMap = {};
        // Obtém a diferença entre o horário local e o UTC em minutos
        let offsetMinutes = new Date().getTimezoneOffset();

        // Converte a diferença de minutos para segundos
        let offsetSeconds = offsetMinutes * 60;
        timestamps.forEach(ts => {
            // Criar a data e hora no UTC
            let ts2 = ts - offsetSeconds

            let date = new Date(ts2 * 1000); // Convertendo o timestamp para milissegundos
            let dateStr = date.toISOString().split('T')[0]; // YYYY-MM-DD
            let hour = date.getUTCHours(); // Obtendo a hora em UTC

            // Adicionar log para verificação

            // Verificar se a hora já foi adicionada para aquele dia específico
            if (!dateMap[dateStr]) {
                dateMap[dateStr] = new Set();
            }

            // Adicionar a hora ao conjunto daquele dia
            dateMap[dateStr].add(hour);
        });

        return dateMap;
    }



    extractAvailableYearsAndMonths(timestamps) {
        let yearMonthMap = {};

        timestamps.forEach(ts => {
            let date = new Date(ts * 1000);
            let year = date.getFullYear();
            let month = date.getMonth(); // 0-11

            if (!yearMonthMap[year]) {
                yearMonthMap[year] = new Set();
            }
            yearMonthMap[year].add(month);
        });

        // Ordena os anos e meses
        Object.keys(yearMonthMap).forEach(year => {
            yearMonthMap[year] = Array.from(yearMonthMap[year]).sort((a, b) => a - b);
        });

        return yearMonthMap;
    }

    renderCalendar() {
        this.container.innerHTML = "";

        let header = document.createElement("div");
        header.className = "header";
        header.innerHTML = `
            <button onclick="calendar.changeMonth(-1)">&lt;</button>
            <div class="dropdowns">
                <select id="monthSelect" onchange="calendar.setMonth(this.value)">
                    ${this.availableYears[this.currentYear]?.map(month => `
                        <option value="${month}" ${month === this.currentMonth ? "selected" : ""}>
                            ${new Date(0, month).toLocaleString('pt-BR', { month: 'long' })}
                        </option>`).join("")}
                </select>
                <select id="yearSelect" onchange="calendar.setYear(this.value)">
                    ${Object.keys(this.availableYears).map(year => `
                        <option value="${year}" ${year == this.currentYear ? "selected" : ""}>${year}</option>`).join("")}
                </select>


            <select id="hourSelect" onchange="calendar.setHour(this.value)">
                ${this.selectedDate ? [...this.availableDates[this.selectedDate]].map(hour => {
            // Cria um objeto Date com o timestamp ajustado para o horário da Espanha (CET)
            const utcDate = new Date(Date.UTC(
                this.selectedDate.split('-')[0], // Ano
                this.selectedDate.split('-')[1] - 1, // Mês (0-indexed)
                this.selectedDate.split('-')[2], // Dia
                hour, 0, 0 // Hora, minuto e segundo
            ));

            // Ajusta para o fuso horário local do usuário
            const userDate = new Date(utcDate.toLocaleString("en-US", { timeZone: "Europe/Madrid" }));

            // Formata a data completa (ano-mês-dia hora:00)
            const fullDate = `${this.selectedDate} ${String(hour).padStart(2, '0')}:00`;  // Adiciona 0 se hora < 10

            return `
                    <option value="${hour}" ${hour == this.selectedHour ? "selected" : ""}>
                        ${String(hour).padStart(2, '0')}:00  <!-- Adiciona 0 se necessário -->
                    </option>`;
        }).join("") : ""}
            </select>

            </div>
            <button onclick="calendar.changeMonth(1)">&gt;</button>
        `;
        this.container.appendChild(header);

        let daysContainer = document.createElement("div");
        daysContainer.className = "grid";
        let firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
        let daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            let empty = document.createElement("div");
            daysContainer.appendChild(empty);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            let dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            let dayElement = document.createElement("button");
            dayElement.innerText = day;

            if (this.availableDates[dateStr]) {
                dayElement.classList.add("available");
                if (this.selectedDate === dateStr) {
                    dayElement.classList.add("selected");
                }
                dayElement.onclick = () => this.selectDate(dateStr);
            } else {
                dayElement.classList.add("disabled");
            }
            daysContainer.appendChild(dayElement);
        }
        this.container.appendChild(daysContainer);
    }

    selectDate(dateStr) {
        this.selectedDate = dateStr;
        this.selectedHour = Math.max(...this.availableDates[dateStr]); // Seleciona última hora disponível

        // Exibe o timestamp da data e hora selecionadas
        //this.displayTimestamp();

        this.renderCalendar();
    }

    setHour(hour) {
        this.selectedHour = parseInt(hour, 10);
        // Exibe o timestamp da data e hora selecionadas
        this.displayTimestamp();
    }

    displayTimestamp() {
        if (this.selectedDate && this.selectedHour !== null) {
            // Creating the date with the selected hour in Spain's time zone (CET)
            const dateParts = this.selectedDate.split('-');
            const selectedDate = new Date(Date.UTC(
                parseInt(dateParts[0]), // Year
                parseInt(dateParts[1]) - 1, // Month (0-indexed)
                parseInt(dateParts[2]), // Day
                this.selectedHour, 0, 0 // Hour, minute, and second
            ));

            // Adjusts to the user's local time zone
            const localDate = new Date(selectedDate.toLocaleString("pt-BR", { timeZone: "America/Sao_Paulo" }));

            // Get the timestamp in milliseconds
            const timestamp = localDate.getTime();

            // Here you can display this timestamp in the interface if necessary
            return timestamp;
        }
    }

    changeMonth(delta) {
        let months = this.availableYears[this.currentYear];
        let currentIndex = months.indexOf(this.currentMonth);

        if (currentIndex !== -1) {
            let newIndex = currentIndex + delta;

            if (newIndex < 0) {
                let years = Object.keys(this.availableYears).map(Number).sort((a, b) => a - b);
                let yearIndex = years.indexOf(this.currentYear);
                if (yearIndex > 0) {
                    this.currentYear = years[yearIndex - 1];
                    this.currentMonth = this.availableYears[this.currentYear].slice(-1)[0];
                }
            } else if (newIndex >= months.length) {
                let years = Object.keys(this.availableYears).map(Number).sort((a, b) => a - b);
                let yearIndex = years.indexOf(this.currentYear);
                if (yearIndex < years.length - 1) {
                    this.currentYear = years[yearIndex + 1];
                    this.currentMonth = this.availableYears[this.currentYear][0];
                }
            } else {
                this.currentMonth = months[newIndex];
            }
        }

        this.renderCalendar();
    }

    setMonth(month) {
        this.currentMonth = parseInt(month, 10);
        this.renderCalendar();
    }

    setYear(year) {
        this.currentYear = parseInt(year, 10);
        this.currentMonth = this.availableYears[this.currentYear][0];
        this.renderCalendar();
    }
}

async function fetchTimestamps() {
    const url = "/calendario/GetTimeStamps.php"; // URL do seu arquivo PHP que retorna os timestamps
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Erro HTTP! Status: ${response.status}`);
        }
        const data = await response.json();

        window.calendar = new CustomCalendar("calendar", "hours", data);
    } catch (error) {
        console.error("Erro ao obter dados:", error);
    }
}

fetchTimestamps();
// Espera o botão de pesquisa de timestamp ser clicado
async function updateStations() {
    const timestamp = window.calendar.displayTimestamp() - 3600000; // Obtém o valor do timestamp

    // Cria a URL para a requisição com o timestamp
    removeMarkers();
    const stationData = await fetchStationData(timestamp); // Obtém os dados das estações via PHP
    stationData.forEach(station => {
        processStationData(station); // Processa e adiciona os marcadores no mapa
    });
}
// Função para atualizar os dados das estações e o gráfico
async function updateStations() {
    try {
        const timestamp = window.calendar.displayTimestamp() - 3600000;
        removeMarkers();
        const stationData = await fetchStationData(timestamp);

        if (!stationData || stationData.length === 0) {
            console.warn("Nenhuma estação encontrada.");
            return;
        }

        stationData.forEach(station => processStationData(station));

        await updateGraph(stationData[0]);
    } catch (error) {
        console.error("Erro ao atualizar as estações:", error);
    }
}

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