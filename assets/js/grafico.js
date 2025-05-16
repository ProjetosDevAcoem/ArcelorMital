fetch('grafico.php')
    .then(response => response.json())
    .then(data => {
        // Convertendo os TimeStamps para formato de hora
        const horas = data.map(item => item.hor_coleta);
        const valores = data.map(item => item.PM10AVG1H);

        const trace1 = {
            x: horas,  // Agora as horas são mostradas corretamente no eixo X
            y: valores, // Valores dos dados no eixo Y
            type: 'bar',
            marker: { color: 'blue' }
        };

        const layout = {
            title: 'Database Data Chart',
            xaxis: { title: 'Hora da coleta' },
            yaxis: { title: 'Valor PM10AVG1H' }
        };

        Plotly.newPlot('chart', [trace1], layout);
    })
    .catch(error => console.error('Erro ao carregar dados:', error));
