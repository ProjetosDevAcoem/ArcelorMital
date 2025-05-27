let colunasAtuais = [];

// Função para pegar as colunas selecionadas no modal ou no formulário
function pegarColunasSelecionadas() {
    return Array.from(document.querySelectorAll('input[name="columns[]"]:checked'))
        .map(checkbox => checkbox.value);
}

// Função para aplicar visibilidade nas colunas da tabela (mostrar só colunas selecionadas)
function aplicarVisibilidadeColunas() {
    // Primeiro, esconde todas as colunas
    colunasAtuais.forEach(coluna => {
        document.querySelectorAll(`.coluna-${coluna}`).forEach(td => {
            td.style.display = 'none';
        });
    });

    // Mostra só as colunas selecionadas
    const selecionadas = pegarColunasSelecionadas();
    selecionadas.forEach(coluna => {
        document.querySelectorAll(`.coluna-${coluna}`).forEach(td => {
            td.style.display = '';
        });
    });
}

// Evento submit do formulário de filtro
document.getElementById('filterForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const station = document.getElementById('stationSelect').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const filtrarBtn = document.getElementById('filtrarBtn');
    const spinner = document.getElementById('filtrarSpinner');

    const colunasSelecionadas = pegarColunasSelecionadas();

    if (!station || !startDate || !endDate) {
        alert('Por favor, selecione estação, data de início e fim.');
        return;
    }
    if (colunasSelecionadas.length === 0) {
        alert('Selecione pelo menos uma coluna para exibir.');
        return;
    }

    filtrarBtn.disabled = true;
    spinner.classList.remove('d-none');

    // Monta URL com parâmetros, repetindo columns[]
    const params = new URLSearchParams({
        station,
        startDate,
        endDate,
    });
    colunasSelecionadas.forEach(c => params.append('columns[]', c));

    fetch(`../back/selectTabela.php?${params.toString()}`)
        .then(response => response.json())
        .then(json => {
            const tbody = document.getElementById('dadosTabela');
            const thead = document.getElementById('theadTabela');

            if (json.erro) {
                tbody.innerHTML = `<tr><td colspan="99">${json.erro}</td></tr>`;
                thead.innerHTML = '';
                colunasAtuais = [];
                return;
            }

            if (!json.data || json.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="99">Nenhum dado encontrado para os filtros selecionados.</td></tr>`;
                thead.innerHTML = '';
                colunasAtuais = [];
                return;
            }

            colunasAtuais = Object.keys(json.data[0]);

            // Cabeçalho
            thead.innerHTML = '';
            const trHead = document.createElement('tr');
            colunasAtuais.forEach(col => {
                const th = document.createElement('th');
                th.className = `coluna-${col}`;
                th.textContent = col;
                trHead.appendChild(th);
            });
            thead.appendChild(trHead);

            // Corpo
            tbody.innerHTML = '';
            json.data.forEach(row => {
                let tr = document.createElement('tr');
                colunasAtuais.forEach(col => {
                    const td = document.createElement('td');
                    td.className = `coluna-${col}`;
                    td.textContent = row[col] !== null ? row[col] : '';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });

            aplicarVisibilidadeColunas();
        })
        .catch(err => {
            console.error('Erro ao buscar dados:', err);
            const tbody = document.getElementById('dadosTabela');
            const thead = document.getElementById('theadTabela');
            tbody.innerHTML = `<tr><td colspan="99">Erro ao carregar dados.</td></tr>`;
            thead.innerHTML = '';
            colunasAtuais = [];
        })
        .finally(() => {
            spinner.classList.add('d-none');
            filtrarBtn.disabled = false;
        });
});

// Botão para baixar CSV
document.getElementById('baixarCSV').addEventListener('click', function () {
  const station = document.getElementById('stationSelect').value;
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;
  const colunasSelecionadas = pegarColunasSelecionadas();

  if (!station || !startDate || !endDate || colunasSelecionadas.length === 0) {
    alert('Você precisa aplicar um filtro antes de exportar.');
    return;
  }

  const params = new URLSearchParams({
    station,
    startDate,
    endDate,
  });
  colunasSelecionadas.forEach(c => params.append('columns[]', c));

  const url = `../back/selectTabela.php?${params.toString()}&export=csv`;

  // Cria um link invisível e dispara o download
  const a = document.createElement('a');
  a.href = url;
  a.download = 'dados.csv'; // nome sugerido para o arquivo
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
});

// Botão para baixar XML
document.getElementById('baixarXML').addEventListener('click', function () {
  const station = document.getElementById('stationSelect').value;
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;
  const colunasSelecionadas = pegarColunasSelecionadas();

  if (!station || !startDate || !endDate || colunasSelecionadas.length === 0) {
    alert('Você precisa aplicar um filtro antes de exportar.');
    return;
  }

  const params = new URLSearchParams({
    station,
    startDate,
    endDate,
  });
  colunasSelecionadas.forEach(c => params.append('columns[]', c));

  const url = `../back/selectTabela.php?${params.toString()}&export=xml`;

  // Cria um link invisível e dispara o download
  const a = document.createElement('a');
  a.href = url;
  a.download = 'dados.xml'; // nome sugerido para o arquivo
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
});
