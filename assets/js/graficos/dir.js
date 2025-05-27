document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".station-btn");
    const startDateInput = document.getElementById("startDate");
    const endDateInput = document.getElementById("endDate");

    let selectedStation = document.querySelector(".station-btn.active")?.getAttribute("data-station");

    // 🔥 Atualiza o gráfico ao mudar os timestamps
    startDateInput.addEventListener("change", () => atualizarGrafico());
    endDateInput.addEventListener("change", () => atualizarGrafico());

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            selectedStation = this.getAttribute("data-station");

            // Remove a classe 'active' de todos os botões e adiciona na selecionada
            buttons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            gerarGraficoDirecao(selectedStation);
        });
    });

    function atualizarGrafico() {
        if (selectedStation) {
            gerarGraficoDirecao(selectedStation);
        }
    }

    if (selectedStation) {
        gerarGraficoDirecao(selectedStation);
    }
});

function gerarGraficoDirecao(station) {
    // ðŸ”¥ Pegando os valores das datas dos inputs no HTML
    const startDateInput = document.getElementById("startDate").value;
    const endDateInput = document.getElementById("endDate").value;

    // ðŸ”¥ Trava para garantir que todos os campos foram preenchidos
    if (!station || !startDateInput || !endDateInput) {
        console.log("Preencha todos os campos antes de gerar o grÃ¡fico.");
        return;
    }

    // Criando a URL da requisiÃ§Ã£o com os filtros de data
    const url = `meteo.php?station=${station}&startDate=${startDateInput}&endDate=${endDateInput}`;

    fetch(url)
        .then(response => response.json())
        .then(response => {
            if (!response.data || response.data.length === 0) {
                console.warn("Nenhum dado encontrado para a estaÃ§Ã£o selecionada.");
                return;
            }

            let xDados = [];
            let yDados = [];
            let labels = [];
            let stationName = response.station_name; // Nome correto da estaÃ§Ã£o

            response.data.forEach(dado => {
                if (dado.TimeStamp && dado.dir !== undefined) {
                    xDados.push(new Date(dado.TimeStamp * 1000));
                    yDados.push(dado.dir);
                    labels.push(`Direção: ${dado.dir}°`);
                }
            });

            var trace = {
                x: xDados,
                y: yDados,
                mode: "markers",
                type: "scatter",
                name: "Direção do Vento",
                text: labels,
                hoverinfo: "text+x+y",
                marker: { size: 8, color: "blue" },
                yaxis: "y"
            };

            var layout = {
                title: {
                    text: `Direção do Vento ${stationName}`,
                    font: { size: 24 },
                    xref: "paper",
                    x: 0.5
                },
                xaxis: {
                    title: "Data e Hora",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: false
                },
                yaxis: {
                    title: "Direção do Vento (Â°)",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: false,
                    range: [0, 360],
                    tickmode: "array",
                    tickvals: [0, 22.5, 45, 67.5 , 90, 112.5, 135, 157.5, 180, 202.5, 225, 247.5, 270, 292.5, 315, 337.5, 360],
                    ticktext: ["0Â°" , "22,5Â°" , "45Â°" , "67,5Â°" , "90Â°", "112,5Â°", "135Â°", "157,5Â°", "180Â°", "202.5Â°", "225Â°", "247,5Â°", "270Â°", "292,5Â°", "315Â°", "337,5Â°", "360Â°"],
                    side: "left",
                    tickfont: { size: 12, color: "black" }
                },
                legend: {
                    x: 1.1,
                    y: 1,
                    orientation: "v"
                }
            };            

            Plotly.newPlot("dir", [trace], layout, { responsive: true });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
}
