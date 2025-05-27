<script>

    document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('startDate').addEventListener('change', gerarGraficoAQI);
    document.getElementById('endDate').addEventListener('change', gerarGraficoAQI);
    document.getElementById('element').addEventListener('change', gerarGraficoAQI);
    carregarEstacoes();
    });

    // Função para carregar as estações
    function carregarEstacoes() {
        fetch('../file.php') // Chama o PHP que retorna as estações
            .then(response => response.json())
            .then(estacoes => {
                const select = document.getElementById('tag');
                select.innerHTML = '<option value="">Selecione uma estação</option>';

                estacoes.forEach(estacao => {
                    const option = document.createElement('option');
                    option.value = estacao.SerialNumber;
                    option.textContent = estacao.Tag;
                    select.appendChild(option);
                });

                select.addEventListener('change', gerarGraficoAQI);
                document.getElementById('startDate').addEventListener('change', gerarGraficoAQI);
                document.getElementById('endDate').addEventListener('change', gerarGraficoAQI);
            })
            .catch(error => console.error('Erro ao carregar estações:', error));
    }

    function gerarGraficoAQI() {
        const nomeEstacao = document.getElementById('tag').value;
        const startDateInput = document.getElementById('startDate').value;
        const endDateInput = document.getElementById('endDate').value;
        const selectedElement = document.getElementById('element').value;

        if (!nomeEstacao || !startDateInput || !endDateInput || !selectedElement) {
            console.log("Preencha todos os campos antes de gerar o gráfico.");
            return;
        }

        let url = `../conn/PoluentesDados.php?tag=${encodeURIComponent(nomeEstacao)}`;
        url += `&startDate=${encodeURIComponent(startDateInput)}`;
        url += `&endDate=${encodeURIComponent(endDateInput)}`;
        url += `&selectedElement=${encodeURIComponent(selectedElement)}`;

        fetch(url)
            .then(response => response.json())
            .then(dados => {
                if (!dados || !dados[nomeEstacao]) {
                    console.log(`Nenhum dado encontrado para ${nomeEstacao}`);
                    Plotly.newPlot('chart', [], { title: "Nenhum dado disponível" });
                    return;
                }

                let dadosEstacao = dados[nomeEstacao].filter(item =>
                    item[selectedElement] !== null && item[selectedElement] !== undefined
                );

                if (dadosEstacao.length === 0) {
                    console.log(`Nenhum dado disponível para ${selectedElement}.`);
                    Plotly.newPlot('chart', [], { title: `Nenhum dado disponível para ${selectedElement}` });
                    return;
                }

                const valores = dadosEstacao.map(item => item[selectedElement]);
                const time = dadosEstacao.map(item => {
                    const timestamp = Number(item.TimeStamp);
                    const date = new Date(timestamp * 1000);
                    date.setHours(date.getHours() - 1);
                    return date.toLocaleString('pt-BR');
                });

                const cores = valores.map(valor => {
                    if (valor <= 40) return "#00e400";
                    if (valor <= 80) return "#f7d400";
                    if (valor <= 120) return "#ff7e00";
                    if (valor <= 200) return "#ff0000";
                    if (valor <= 300) return "#950e61";
                    return "#7e0023"; // Acima de 300 - extremamente ruim
                });

                const trace = {
                    x: time,
                    y: valores,
                    type: 'bar',
                    name: selectedElement.toUpperCase(),
                    marker: { color: cores }
                };

                const selectedElementText = document.getElementById('element').selectedOptions[0].text;
                const stationText = document.getElementById('tag').selectedOptions[0].text;

                const layout = {
                    title: {
                        text: `Monitoramento de ${selectedElementText} - ${stationText}`,
                        font: { size: 18 },
                        x: 0.5
                    },
                    xaxis: { title: 'Hora de Coleta' },
                    yaxis: {
                        title: selectedElementText,
                        range: [0, 200]
                    }
                };

                Plotly.newPlot('chart', [trace], layout);
            })
            .catch(error => console.error('Erro ao carregar os dados:', error));
    }

    document.getElementById('downloadCsv').addEventListener('click', async function () {
        const nomeEstacao = document.getElementById('tag').value;
        const startDateInput = document.getElementById('startDate').value;
        const endDateInput = document.getElementById('endDate').value;
        const selectedElement = document.getElementById('element').value;

        if (!nomeEstacao || !startDateInput || !endDateInput || !selectedElement) {
            alert("Preencha todos os campos antes de baixar o CSV.");
            return;
        }

        let url = `../conn/PoluentesDados.php?tag=${encodeURIComponent(nomeEstacao)}`;
        url += `&startDate=${encodeURIComponent(startDateInput)}`;
        url += `&endDate=${encodeURIComponent(endDateInput)}`;
        url += `&selectedElement=${encodeURIComponent(selectedElement)}`;

        try {
            const response = await fetch(url);
            const dados = await response.json();

            if (!dados || !dados[nomeEstacao] || dados[nomeEstacao].length === 0) {
                alert("Nenhum dado disponível para download.");
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "TimeStamp," + selectedElement + "\n";

            dados[nomeEstacao].forEach(item => {
                const timestamp = item.TimeStamp ? new Date(item.TimeStamp * 1000).toLocaleString('pt-BR') : "Data inválida";
                const valor = item[selectedElement] ?? "";
                csvContent += `${timestamp},${valor}\n`;
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "dados.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (error) {
            console.error('Erro ao obter os dados:', error);
            alert("Erro ao obter os dados.");
        }
    });
</script>