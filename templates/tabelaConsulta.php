<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include '../partials/head.php';
include '../partials/header.php';
?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-100">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title id="page-title"><?= $lang['page_title_met'] ?></title>

  <!-- CSS principal -->
  <link rel="stylesheet" href="../assets/css/normalize.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../assets/css/cabecalho.css" />
  <link rel="stylesheet" href="../assets/css/grid/cabecalho-grid.css" />
  <link rel="stylesheet" href="../assets/css/mapa.css" />
  <link rel="stylesheet" href="../assets/css/responsivo.css" />
  <link rel="stylesheet" href="../assets/css/calendario.css" />
  <link rel="stylesheet" href="../assets/css/rodape.css" />
  <link rel="stylesheet" href="../assets/css/cabecalho-botoes.css" />
  <link rel="stylesheet" href="../assets/css/grid/elementosAQI.css" />
  <link rel="stylesheet" href="../assets/css/lateral-direita.css" />
  <link rel="stylesheet" href="../assets/css/enhanced-style.css" />
  <link rel="stylesheet" href="../assets/css/meteo.css" />
  <link rel="stylesheet" href="../assets/css/monitoring.css" />
  <link rel="stylesheet" href="../assets/css/tabela.css" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Se o head.php só contém CSS/JS adicionais, pode incluir aqui -->
</head>
  <body class="d-flex flex-column h-100">
    <main class="flex-fill">
      <div class="container-fluid py-4">
        <div class="card shadow mb-4 w-100">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">
              <i class="fas fa-chart-line me-2"></i>Dados por Estação e Data
            </h2>
          </div>

          <div class="card-body">
            <form id="filterForm" class="row g-3 align-items-end">
              <div class="col-md-3">
                <label for="stationSelect" class="form-label">Estação</label>
                <select id="stationSelect" name="station" class="form-select" required>
                  <option value="">Selecione</option>
                  <option value="station1">São Carlos</option>
                  <option value="station2">Restinga</option>
                  <option value="station3">AMRIGS</option>
                  <option value="station4">Unidade Móvel</option>
                  <option value="station5">Moacyr Scliar</option>
                  <option value="station6">Rodoviária</option>
                </select>
              </div>

              <div class="col-md-3">
                <label for="startDate" class="form-label">Início</label>
                <input type="datetime-local" id="startDate" name="startDate" class="form-control" required />
              </div>

              <div class="col-md-3">
                <label for="endDate" class="form-label">Fim</label>
                <input type="datetime-local" id="endDate" name="endDate" class="form-control" required />
              </div>

              <div class="col-md-3 d-flex justify-content-end">
                <button type="submit" id="filtrarBtn" class="btn btn-primary w-100">
                  <span id="filtrarSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                  Filtrar
                </button>
              </div>
            </form>
          </div>

          <!-- Modal mínimo continua dentro do body, mas fora do form -->
          <div class="modal fade" id="colunaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content p-3">
                <h5>Selecionar Colunas</h5>
                
                <label><input type="checkbox" name="columns[]" value="TimeStamp" checked> Data/Hora</label><br>
                <label><input type="checkbox" name="columns[]" value="Tag" checked> Tag</label><br>
                <label><input type="checkbox" name="columns[]" value="temp" checked> Temperatura</label><br>
                <label><input type="checkbox" name="columns[]" value="umid" checked> Umidade</label><br>
                <label><input type="checkbox" name="columns[]" value="press" checked> Pressão</label><br>
                <label><input type="checkbox" name="columns[]" value="vel" checked> Velocidade do Vento</label><br>
                <label><input type="checkbox" name="columns[]" value="chuva" checked> Chuva</label><br>
                <label><input type="checkbox" name="columns[]" value="rad" checked> Radiação</label><br>
                <label><input type="checkbox" name="columns[]" value="dir" checked> Direção</label><br>   

                <style>
                  .botaoSalvar{
                    width: auto;
                  }
                </style>
                <button type="button" class="btn btn-primary mt-3 botaoSalvar" data-bs-dismiss="modal" id="btnSalvarColunas">
                  Salvar
                </button>
              </div>
            </div>
          </div>

            
            <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
              <div class="btn-toolbar" role="toolbar">
                <div class="btn-group me-2" role="group">
                  <button id="baixarCSV" class="btn btn-success btn-custom">
                    <i class="fas fa-file-csv"></i><span class="ms-1">CSV</span>
                  </button>
                  <button id="baixarXML" class="btn btn-info btn-custom">
                    <i class="fas fa-file-code"></i><span class="ms-1">XML</span>
                  </button>
                </div>
                <button id="checkboxes" type="button" class="btn btn-secondary btn-custom btn-colunas"
                  data-bs-toggle="modal" data-bs-target="#colunaModal">
                  <i class="fas fa-columns"></i><span class="ms-1">Selecionar Colunas</span>
                </button>
              </div>
            </div>

            <div class="table-responsive shadow rounded-3 my-4">
            <table id="tabelaDados" class="table table-striped table-bordered">
              <thead id="theadTabela">
                <tr style="display: none";>
                  <th class="coluna-TimeStamp">TimeStamp</th>
                  <th class="coluna-Tag">Tag</th>
                  <th class="coluna-temp">Temperatura</th>
                  <th class="coluna-umid">Umidade</th>
                  <th class="coluna-press">Pressão</th>
                  <th class="coluna-vel">Velocidade</th>
                  <th class="coluna-chuva">Chuva</th>
                  <th class="coluna-rad">Radiação</th>
                </tr>
              </thead>
              <tbody id="dadosTabela">
              <?php
              if (!empty($dados)) {
                  foreach ($dados as $linha) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($linha['TimeStamp']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['Tag']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['temp']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['umid']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['press']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['vel']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['chuva']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['rad']) . "</td>";
                      echo "<td>" . htmlspecialchars($linha['dir']) . "</td>";
                      echo "</tr>";
                  }
              } else {
                  echo '<tr><td colspan="9" class="text-center d-none">Nenhum dado disponível para os filtros selecionados.</td></tr>';
              }
              ?>
              </tbody>
            </table>
              <div id="noDataAlert" class="alert alert-warning text-center d-none">
                Nenhum dado disponível para os filtros selecionados.
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script src="../assets/js/gerartabela.js"></script>
  </body>
</html>

<style>
/* Botões mais atraentes */
.btn-custom {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;       /* espaço entre ícone e texto */
  white-space: nowrap;/* mantém todo o texto na mesma linha */
  border-radius: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  padding: 0.5rem 1rem;
  min-width: 120px;        /* ajuste conforme o texto */
  transition: transform 0.2s, box-shadow 0.2s;
}

.btn-custom:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Cabeçalho do card com gradiente */
.card-header {
  background: linear-gradient(90deg, #0062e6, #33aeff);
  border: none;
}

/* Sombra mais marcante */
.card.shadow {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Botão “Atualizar” maior */
.btn-refresh {
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  min-width: 160px;       /* aumente se precisar */
}

/* Botão “Selecionar Colunas” maior, para caber todo o texto */
.btn-colunas {
  min-width: 200px;       /* ajuste esse valor até caber “Selecionar Colunas” */
  padding: 0.75rem 1.25rem;
}
.editable-cell {
  background-color: #fff;      /* fundo branco */
  border: 1.5px solid #007bff; /* borda azul mais forte */
  cursor: text;
  padding: 3px 5px;
  border-radius: 3px;
  min-width: 80px;
  outline: none;
  transition: box-shadow 0.2s ease-in-out;
}

.editable-cell:focus {
  box-shadow: 0 0 5px #007bff;
  background-color: #e6f0ff;
}
</style>