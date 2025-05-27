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

            gerarGraficoUmidade(selectedStation);
        });
    });

    function atualizarGrafico() {
        if (selectedStation) {
            gerarGraficoUmidade(selectedStation);
        }
    }

    if (selectedStation) {
        gerarGraficoUmidade(selectedStation);
    }
});

function gerarGraficoUmidade(station) {
    const startDateInput = document.getElementById("startDate").value;
    const endDateInput = document.getElementById("endDate").value;

    if (!station) {
        console.log("Nenhuma estação selecionada.");
        return;
    }

    if (!startDateInput || !endDateInput) {
        console.log("Preencha o intervalo de tempo antes de gerar o gráfico.");
        return;
    }

    const url = `meteo.php?station=${station}&startDate=${startDateInput}&endDate=${endDateInput}`;

    fetch(url)
        .then(response => response.json())
        .then(response => {
            if (!response.data || response.data.length === 0) {
                console.warn("Nenhum dado encontrado para a estação selecionada.");
                Plotly.newPlot("umid", [], { title: "Nenhum dado disponível" });
                return;
            }

            let xDados = [];
            let yDados = [];
            let labels = [];
            let stationName = response.station_name || "Desconhecido";

            response.data.forEach(dado => {
                if (dado.TimeStamp && dado.umid !== undefined) {
                    xDados.push(new Date(dado.TimeStamp * 1000));
                    yDados.push(dado.umid);
                    labels.push(`Umidade: ${dado.umid}%`);
                }
            });

            let minY = yDados.length > 0 ? Math.min(...yDados) - 2 : 0;
            let maxY = yDados.length > 0 ? Math.max(...yDados) + 2 : 100;

            var trace = {
                x: xDados,
                y: yDados,
                mode: "lines+markers",
                type: "scatter",
                name: "Umidade",
                text: labels,
                hoverinfo: "text+x+y",
                marker: { size: 8, color: "blue" },
                yaxis: "y"
            };

            var layout = {
                title: {
                    text: `Umidade - ${stationName}`,
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
                    title: "Umidade (%)",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: false,
                    range: [0, 100],
                    tickmode: "linear",
                    tick0: 0,     // Início dos ticks em 0
                    dtick: 10     // Intervalo de 10 em 10
                },                
                legend: {
                    x: 1.1,
                    y: 1,
                    orientation: "v"
                }
            };

            Plotly.newPlot("umid", [trace], layout, { responsive: true });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
}
