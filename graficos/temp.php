<php?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".station-btn");
    const startDateInput = document.getElementById("startDate");
    const endDateInput = document.getElementById("endDate");

    let selectedStation = document.querySelector(".station-btn.active")?.getAttribute("data-station");

    // Evento para atualizar o gráfico ao mudar os timestamps
    startDateInput.addEventListener("change", () => atualizarGrafico());
    endDateInput.addEventListener("change", () => atualizarGrafico());

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            selectedStation = this.getAttribute("data-station");

            // Remove a classe 'active' de todos os botões e adiciona na selecionada
            buttons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            gerarGraficoTemperatura(selectedStation);
        });
    });

    function atualizarGrafico() {
        if (selectedStation) {
            gerarGraficoTemperatura(selectedStation);
        }
    }

    if (selectedStation) {
        gerarGraficoTemperatura(selectedStation);
    }
});

const chart_temp_title = "<?= $lang['chart_temp_title'] ?>";


function gerarGraficoTemperatura(station) {
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
                Plotly.newPlot("temp", [], { title: "Nenhum dado disponível" });
                return;
            }

            let xDados = [];
            let yDados = [];
            let labels = [];
            let stationName = response.station_name || "Desconhecido";

            response.data.forEach(dado => {
                if (dado.TimeStamp && dado.temp !== undefined) {
                    xDados.push(new Date(dado.TimeStamp * 1000));
                    yDados.push(dado.temp);
                    labels.push(`Temperatura: ${dado.temp}°C`);
                }
            });

            let minY = yDados.length > 0 ? Math.min(...yDados) - 2 : 0;
            let maxY = yDados.length > 0 ? Math.max(...yDados) + 2 : 10;

            var trace = {
                x: xDados,
                y: yDados,
                mode: "lines+markers",
                type: "scatter",
                name: "Temperatura",
                text: labels,
                hoverinfo: "text+x+y",
                marker: { size: 8, color: "blue" },
                line: { color: "blue", width: 2 },
                yaxis: "y"
            };

            var layout = {
                title: {
                    text: `${chart_temp_title} - ${stationName}`,
                    font: { size: 22 },
                    xref: "paper",
                    x: 0.5
                },
                xaxis: {
                    title: "Data e Hora",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: false,
                    tickformat: "%d/%m %H:%M"
                },
                yaxis: {
                    title: "Temperatura (°C)",
                    titlefont: { size: 14 },
                    showgrid: true,
                    zeroline: true,
                    range: [minY, 60],
                    tickmode: "auto",
                    nticks: 6,
                    automargin: true
                },
                legend: {
                    x: 0.02,
                    y: 1.1,
                    orientation: "h"
                }
            };

            Plotly.newPlot("temp", [trace], layout, { responsive: true });
        })
        .catch(error => console.error("Erro ao carregar os dados:", error));
}

</script>    
</php?>
