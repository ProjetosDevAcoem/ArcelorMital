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

            gerarGraficoVelocidade(selectedStation);
        });
    });

    function atualizarGrafico() {
        if (selectedStation) {
            gerarGraficoVelocidade(selectedStation);
        }
    }

    if (selectedStation) {
        gerarGraficoVelocidade(selectedStation);
    }
});

function gerarGraficoVelocidade(station) {
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
            let stationName = response.station_name;

            response.data.forEach(dado => {
                if (dado.TimeStamp && dado.vel !== undefined) {
                    xDados.push(new Date(dado.TimeStamp * 1000));
                    yDados.push(dado.vel);
                    labels.push(`Velocidade: ${dado.vel} m/s`);
                }
            });

            var trace = {
                x: xDados,
                y: yDados,
                mode: "lines+markers",
                type: "scatter",
                name: "Velocidade do Vento",
                text: labels,
                hoverinfo: "text+x+y",
                marker: { size: 8, color: "blue" },
                yaxis: "y"
            };

            var layout = {
                title: {
                    text: `Velocidade do Vento - ${stationName}`,
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
                    title: "Velocidade do Vento (m/s)",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: false,
                    tickmode: "linear"
                },
                legend: {
                    x: 1.1,
                    y: 1,
                    orientation: "v"
                }
            };

            Plotly.newPlot("vel", [trace], layout, { responsive: true });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
}
