<script>
// Este idioma está sendo trocado normalmente no header do site
const langMap = {
    pt: 'pt-BR',
    en: 'en-US',
    es: 'es-ES'
};

document.addEventListener("DOMContentLoaded", function () {
    carregarDadosEstacao();
});

function carregarDadosEstacao() {
    fetch("../back/file.php") // Substitua pelo caminho correto do seu PHP
        .then(response => response.json())
        .then(data => {
            // Aqui: falta definir o que é TableName, se não, vai dar erro
            // Exemplo: definir TableName antes ou receber de algum lugar
            const TableName = "saocarlos"; // <-- você precisa definir isso

            // Filtra para pegar somente a estação desejada
            const estacao = data.find(est => est.TableName === TableName);
            if (estacao) {
                ExibirDados(estacao); // Passa a data formatada para a função ExibirDados
            } else {
                console.error("Estação não encontrada.");
            }
        })
        .catch(error => console.error("Erro ao carregar os dados da estação:", error));
}

function ExibirDados(estacao) {
    if (estacao) {
        // Note que os nomes das propriedades devem bater com as do JSON que vem do PHP
        const tag = `<h2>${estacao.Tag || '--'}<h2>`;
        const temp = `<p>${estacao.temp || '--'}</p>`;
        const umid = `<p>${estacao.umid || '--'}</p>`;
        const vel = `<p>${estacao.vel || '--'}</p>`;
        // No seu código você usou 'pressure' no objeto, mas no PHP está 'press'?
        const pressure = `<p>${estacao.press || '--'}</p>`;
        const radiation = `<p>${estacao.rad || '--'}</p>`;
        const rain = `<p>${estacao.chuva || '--' }</p>`;
        const dir = `<p>${estacao.dir || '--'}</p>`;

        // 'date' está indefinida. Você precisa pegar a data da estação ou formatar o timestamp.
        // Exemplo básico:
        const date = estacao.TimeStamp ? new Date(estacao.TimeStamp * 1000).toLocaleString() : '';
        document.getElementById("StationTitle").innerHTML = tag;
        document.getElementById("date").innerHTML = date;
        document.getElementById("temperature").innerHTML = temp;
        document.getElementById("humidity").innerHTML = umid;
        document.getElementById("wind-speed").innerHTML = vel;
        document.getElementById("pressure").innerHTML = pressure;
        document.getElementById("radiation").innerHTML = radiation;
        document.getElementById("rain").innerHTML = rain;
        document.getElementById("windDirection").innerHTML = dir;
    }
}
</script>
