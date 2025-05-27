<php?>
    <script>
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

                gerarGraficoPressao(selectedStation);
            });
        });

        function atualizarGrafico() {
            if (selectedStation) {
                gerarGraficoPressao(selectedStation);
            }
        }

        if (selectedStation) {
            gerarGraficoPressao(selectedStation);
        }
    });

    const chart_pressure_title = "<?= $lang['chart_pressure_title'] ?>";

    function gerarGraficoPressao(station) {
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
                let stationName = response.station_name || "Desconhecido";

                response.data.forEach(dado => {
                    if (dado.TimeStamp && dado.press !== undefined) {
                        xDados.push(new Date(dado.TimeStamp * 1000));
                        yDados.push(dado.press);
                        labels.push(`Pressão: ${dado.press} hPa`);
                    }
                });

                // Define um range adequado para a pressÃ£o atmosfÃ©rica
                let minY = yDados.length > 0 ? Math.min(...yDados) * 0.98 : 1000;
                let maxY = yDados.length > 0 ? Math.max(...yDados) * 1.02 : 1020;

                if (minY === maxY) {
                    maxY = minY + 5;
                }

                var trace = {
                    x: xDados,
                    y: yDados,
                    mode: "lines+markers",
                    type: "scatter",
                    name: "Pressão Atmosférica",
                    text: labels,
                    hoverinfo: "text+x+y",
                    marker: { size: 8, color: "blue" },
                    line: { color: "blue", width: 2 },
                    yaxis: "y"
                };

                var layout = {
                    title: {
                        text: `${chart_pressure_title} - ${stationName}`,
                        font: { size: 22 },
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
                        title: "Pressão (hPa)",
                        titlefont: { size: 14 },
                        showgrid: true,
                        zeroline: false,
                        range: [800, 1100],
                        tickmode: "auto",
                        nticks: 6,
                        tickformat: ".2f",
                        automargin: true
                    },
                    legend: {
                        x: 1.1,
                        y: 1,
                        orientation: "v"
                    }
                };

                Plotly.newPlot("press", [trace], layout, { responsive: true });
            })
            .catch(error => console.error("Erro ao carregar os dados:", error));
    }

</script>
</php?>

