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
    fetch("file.php") // Substitua pelo caminho correto do seu PHP
        .then(response => response.json())
        .then(data => {
            // Filtra para pegar somente a estação desejada
            const estacao = data.find(est => est.SerialNumber === "emqarmovel_iqa");

            // Opções corretas para formatação de data
            const opcoes = { 
                day: 'numeric', 
                month: 'long', 
                hour: '2-digit', 
                minute: '2-digit', 
                year: 'numeric', 
                timeZone: 'America/Sao_Paulo' 
            };

            let dataFormatada = langStatus.date_unavailable;
            if (estacao.TimeStamp) {
                let timestamp = Number(estacao.TimeStamp);
                if (timestamp < 1e12) timestamp *= 1000;

                const locale = langMap[currentLang] || 'pt-BR'; // fallback em caso de erro
                dataFormatada = new Date(timestamp).toLocaleDateString(locale, opcoes);
            }

            console.log(dataFormatada);

            if (estacao) {
                ExibirDados(estacao, dataFormatada); // Passa a data formatada para a função ExibirDados

                const aqiValue = estacao.iqa_final;
                const parametros = document.getElementById('AQI');

                if (parametros) {
                    // Aplica cor ao fundo com base no AQI
                    parametros.style.backgroundColor = ChangeColor(parametros, aqiValue);
                }

                // Obtém os dados da classificação do AQI
                const classificationData = Classification(aqiValue);

                // Atualiza a interface com a classificação
                document.getElementById("status").innerText = classificationData.classification;
            } else {
                console.error("Estação 'emqarmovel_iqa' não encontrada.");
            }
        })
        .catch(error => console.error("Erro ao carregar os dados da estação:", error));
}

function ExibirDados(estacao, dataFormatada) {
    document.getElementById("title-kunak-station").innerHTML = `<h2>${estacao.Tag}</h2>`;
    document.getElementById("date").innerHTML = `<p>${dataFormatada}</p>`; // Exibe a data formatada
    document.getElementById("AQI").innerHTML = estacao.iqa_final;
    document.getElementById("pm10").innerHTML = estacao.iqa_pm10;
    document.getElementById("pm25").innerHTML = estacao.iqa_pm25;
    document.getElementById("o3").innerHTML = estacao.iqa_o3;
    document.getElementById("co").innerHTML = estacao.iqa_co;
    document.getElementById("so2").innerHTML = estacao.iqa_so2;
    document.getElementById("no2").innerHTML = estacao.iqa_no2;
}

function ChangeColor(parametro, valor) {
    if (valor === null || valor === undefined) return "grey"; // Se for null ou undefined, retorna cinza
    
    if (parametro && parametro.id === "AQI") { // Verifica se o ID do elemento é "AQI"
        if (valor <= 40) return "#00e400"; // Bom
        if (valor <= 80) return "#f7d400"; // Moderado
        if (valor <= 120) return "#ff7e00"; // Ruim
        if (valor <= 200) return "#ff0000"; // Muito Ruim
        if (valor <= 300) return "#950e61"; // Péssimo
    }
    return "grey"; // Caso o valor não esteja em nenhuma faixa definida
}

// Função para classificar o valor do AQI e retornar o ícone apropriado
function Classification(aqi) {
    if (aqi <= 40) {
        return {
            color: '#00e400',
            classification: langStatus.status_good,
            icon: "src/images/icone acoem boa A2.svg",
            Desc: langStatus.desc_good
        };
    } else if (aqi <= 80) {
        return {
            color: '#f7d400',
            classification: langStatus.status_moderate,
            icon: "/src/images/icone acoem moderada A3.svg",
            Desc: langStatus.desc_moderate
        };
    } else if (aqi <= 120) {
        return {
            color: '#ff7e00',
            classification: langStatus.status_bad,
            icon: "/src/images/icone acoem ruim A2.svg",
            Desc: langStatus.desc_bad
        };
    } else if (aqi <= 200) {
        return {
            color: '#ff0000',
            classification: langStatus.status_very_bad,
            icon: "/src/images/icone acoem muito ruim A2.svg",
            Desc: langStatus.desc_very_bad
        };
    } else if (aqi <= 300) {
        return {
            color: '#950e61',
            classification: langStatus.status_terrible,
            icon: "/src/images/icone acoem péssima A2.svg",
            Desc: langStatus.desc_terrible
        };
    } else {
        return {
            color: 'grey',
            classification: langStatus.status_inactive,
            icon: "/src/images/icone acoem desativado A2.svg",
            Desc: langStatus.desc_inactive
        };
    }
}
</script>