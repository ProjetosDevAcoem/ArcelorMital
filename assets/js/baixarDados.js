let dadosParaDownload = [];  // Variável global para armazenar os dados

// Função para gerar CSV
function gerarCSV(dados) {
    const cabecalho = ['Hora de Coleta', 'Valor'];
    const linhas = dados.map(item => `${item.time},${item.valor}`);

    const csvConteudo = [cabecalho.join(','), ...linhas].join('\n');

    const blob = new Blob([csvConteudo], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'dados.csv';
    link.click();
    URL.revokeObjectURL(url);
}

// Função para gerar TXT
function gerarTXT(dados) {
    const conteudo = dados.map(item => `Hora de Coleta: ${item.time}\nValor: ${item.valor}\n`).join('\n');

    const blob = new Blob([conteudo], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'dados.txt';
    link.click();
    URL.revokeObjectURL(url);
}