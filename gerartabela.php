
    <!-- Scripts no final do body -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('filtrarBtn').addEventListener('click', () => {
                const station = document.getElementById('stationSelect').value;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                if (!station || !startDate || !endDate) {
                    alert("Por favor, selecione uma estação e as datas de início e fim.");
                    return;
                }

                fetch(`tabeladados.php?station=${station}&startDate=${startDate}&endDate=${endDate}`)
                    .then(response => response.json())
                    .then(json => {
                        const tbody = document.getElementById('dadosTabela');
                        tbody.innerHTML = '';

                        if (json.erro) {
                            tbody.innerHTML = `<tr><td colspan="9">${json.erro}</td></tr>`;
                            return;
                        }

                        if (!json.data || json.data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="9">Nenhum dado encontrado para os filtros selecionados.</td></tr>';
                            return;
                        }

                        json.data.forEach(row => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${row.TimeStamp}</td>
                                    <td>${row.Tag}</td>
                                    <td>${row.temp}</td>
                                    <td>${row.umid}</td>
                                    <td>${row.press}</td>
                                    <td>${row.vel}</td>
                                    <td>${row.chuva}</td>
                                    <td>${row.rad}</td>
                                    <td>${row.dir}</td>
                                </tr>`;
                        });
                    })
                    .catch(error => {
                        console.error("Erro ao buscar dados:", error);
                        document.getElementById('dadosTabela').innerHTML =
                            '<tr><td colspan="9">Erro ao carregar dados.</td></tr>';
                    });
            });
        });
    </script>