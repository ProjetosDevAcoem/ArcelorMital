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

            gerarGraficoChuva(selectedStation);
        });
    });

    function atualizarGrafico() {
        if (selectedStation) {
            gerarGraficoChuva(selectedStation);
        }
    }

    if (selectedStation) {
        gerarGraficoChuva(selectedStation);
    }
});

function gerarGraficoChuva(station) {
    const startDateInput = document.getElementById("startDate").value;
    const endDateInput = document.getElementById("endDate").value;

    if (!station || !startDateInput || !endDateInput) {
        console.log("Preencha todos os campos antes de gerar o gráfico.");
        return;
    }

    const url = `meteo.php?station=${station}&startDate=${startDateInput}&endDate=${endDateInput}`;

    fetch(url)
        .then(response => response.json())
        .then(response => {
            if (!response.data || response.data.length === 0) {
                console.warn("Nenhum dado encontrado para a estação selecionada.");
                Plotly.newPlot("chuva", [], { title: "Nenhum dado disponível" });
                return;
            }

            let xDados = [];
            let yDados = [];
            let labels = [];
            let stationName = response.station_name;

            response.data.forEach(dado => {
                if (
                    dado.TimeStamp &&
                    dado.chuva != null &&
                    dado.chuva !== "" &&
                    !isNaN(parseFloat(dado.chuva))
                ) {
                    const dataHora = new Date(dado.TimeStamp * 1000);
                    const chuvaValor = parseFloat(dado.chuva);

                    xDados.push(dataHora);
                    yDados.push(chuvaValor);
                    labels.push(`Chuva: ${chuvaValor} mm`);
                }
            });

            let maxY = yDados.length > 0 ? Math.max(...yDados) * 1.2 : 10;

            var trace = {
                x: xDados,
                y: yDados,
                type: "bar",
                name: "Chuva (mm)",
                text: labels,
                hoverinfo: "text+x+y",
                marker: { color: "blue" }
            };

            var layout = {
                title: {
                    text: `Chuva - ${stationName}`,
                    font: { size: 24 },
                    xref: "paper",
                    x: 0.5
                },
                xaxis: {
                    title: "Data e Hora",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: false,
                    tickangle: -45
                },
                yaxis: {
                    title: "Chuva (mm)",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: true,
                    range: [0, 200]
                },
                legend: {
                    x: 1.1,
                    y: 1,
                    orientation: "v"
                }
            };

            Plotly.newPlot("chuva", [trace], layout, { responsive: true });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
}
