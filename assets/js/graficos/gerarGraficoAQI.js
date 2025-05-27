document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('startDate').addEventListener('change', gerarGraficoAQI);
    document.getElementById('endDate').addEventListener('change', gerarGraficoAQI);
    document.getElementById('element').addEventListener('change', gerarGraficoAQI);
    carregarEstacoes();
    // Não chame gerarGraficoAQI aqui; será chamado ao selecionar a estação e data
});

// Função para carregar as estações
function carregarEstacoes() {
    fetch('file.php') // Chama o PHP que retorna as estações
        .then(response => response.json())
        .then(estacoes => {
            const select = document.getElementById('tag');
            select.innerHTML = '<option value="">Selecione uma estação</option>';

            // Adiciona cada estação como opção no select
            estacoes.forEach(estacao => {
                const option = document.createElement('option');
                option.value = estacao.SerialNumber;
                option.textContent = estacao.Tag;
                select.appendChild(option);
            });

            // Após carregar as estações, ouça o evento de mudança de estação e data
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

    let url = `PoluentesDados.php?tag=${encodeURIComponent(nomeEstacao)}`;
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

            let dadosEstacao = dados[nomeEstacao];

            dadosEstacao = dadosEstacao.filter(item =>
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

            // Definição de cores conforme o valor do poluente
            const cores = valores.map(valor => {
                if (valor <= 40) return "#00e400"; // bom
                if (valor <= 80) return "#f7d400"; // Moderado
                if (valor <= 120) return "#ff7e00"; // Ruim
                if (valor <= 200) return "#ff0000"; // Muito Ruim
                if (valor <= 300) return "#950e61"; // Péssima
            });

            const trace = {
                x: time,
                y: valores,
                type: 'bar',
                name: selectedElement.toUpperCase(),
                marker: { color: cores }
            };

            // Obtendo o nome legível do poluente selecionado
            const selectedElementDropdown = document.getElementById('element');
            const selectedElementText = selectedElementDropdown.options[selectedElementDropdown.selectedIndex].text;

            // Obtendo o nome legível da estação selecionada
            const stationDropdown = document.getElementById('tag');
            const stationText = stationDropdown.options[stationDropdown.selectedIndex].text;

            const layout = {
                title: {
                    text: `Monitoramento de ${selectedElementText} - ${stationText}`,
                    font: {
                        size: 18
                    },
                    x: 0.5
                },
                xaxis: { 
                    title: 'Hora de Coleta' 
                },
                yaxis: {
                    title: selectedElementText,
                    range: [0, 200] // Define o limite de 200 no eixo Y
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

    let url = `PoluentesDados.php?tag=${encodeURIComponent(nomeEstacao)}`;
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
        csvContent += "TimeStamp," + selectedElement + "\n"; // Cabeçalho

        dados[nomeEstacao].forEach(item => {
            const timestamp = item.TimeStamp ? new Date(item.TimeStamp * 1000).toLocaleString('pt-BR') : "Data inválida";
            const valor = item[selectedElement] ?? "";
            csvContent += `${timestamp},${valor}\n`;
        });

        // Criando um link para baixar o CSV
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "dados.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // Remove o link após o download

    } catch (error) {
        console.error('Erro ao obter os dados:', error);
        alert("Erro ao obter os dados.");
    }
});
