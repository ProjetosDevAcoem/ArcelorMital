document.addEventListener("DOMContentLoaded", function () {
    const stationButtons = document.querySelectorAll(".station-btn");
    const temperatureElement = document.querySelector("#temperature .value");
    const humidityElement = document.querySelector("#humidity .value");
    const pressureElement = document.querySelector("#pressure .value");
    const velElement = document.querySelector("#wind-speed .value");
    const radiation = document.querySelector("#radiation .value");
    const rain = document.querySelector("#rain .value");
    const windDirection = document.querySelector("#windDirection .value");

    // Função para buscar e exibir os dados
    function fetchStationData(stationName) {
        fetch(`poluentes.php?station=${stationName}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const latestData = data[0]; // Último dado registrado

                    // Atualizar os valores na interface
                    temperatureElement.textContent = latestData.temp ?? "N/A";
                    humidityElement.textContent = latestData.umid ?? "N/A";
                    pressureElement.textContent = latestData.press ?? "N/A";
                    velElement.textContent = latestData.vel ?? "N/A";
                    radiation.textContent = latestData.rad ?? "N/A";
                    rain.textContent = latestData.chuva ?? "N/A";
                    windDirection.textContent = latestData.dir ?? "N/A";
                } else {
                    console.error("Nenhum dado encontrado para essa estação.");
                }
            })
            .catch(error => console.error("Erro ao buscar dados:", error));
    }

    // Definir "São Carlos" como padrão ao carregar a página
    const defaultStation = "station1"; // ID da estação São Carlos
    const defaultButton = document.querySelector(`[data-station="${defaultStation}"]`);
    if (defaultButton) {
        defaultButton.classList.add("active");
        fetchStationData(defaultStation);
    }

    // Evento de clique nos botões
    stationButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Remover classe "active" de todos os botões e adicionar no clicado
            stationButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            // Pegar o nome da estação do botão clicado e buscar os dados
            const stationName = this.getAttribute("data-station");
            fetchStationData(stationName);
        });
    });
});
