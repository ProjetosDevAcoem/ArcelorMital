<?php
try {
    $config = include('../config.php');

    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    date_default_timezone_set('UTC');

    // Parâmetros da requisição
    $station = $_GET['station'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $selectedColumns = $_GET['columns'] ?? []; // espera array de colunas

    if (empty($station)) throw new Exception("Nenhuma estação informada.");
    if (empty($startDate) || empty($endDate)) throw new Exception("Data de início e fim são obrigatórias.");
    if (empty($selectedColumns) || !is_array($selectedColumns)) throw new Exception("Nenhuma coluna selecionada.");

    $stationsTableMap = [
        "station1" => "saocarlos",
        "station2" => "restinga",
        "station3" => "amrigs",
        "station4" => "emqarmovel",
        "station5" => "moacyrscliar",
        "station6" => "rodoviaria"
    ];

    if (!isset($stationsTableMap[$station])) throw new Exception("Estação inválida.");

    $tableName = $stationsTableMap[$station];

    $allowedCols = ['TimeStamp','Tag','temp','umid','press','vel','chuva','rad','dir','status','comentario'];
    $colsFiltered = array_intersect($selectedColumns, $allowedCols);
    if (empty($colsFiltered)) throw new Exception("Nenhuma coluna válida selecionada.");

    $selectCols = implode(',', array_map(fn($c) => "`$c`", $colsFiltered));

    $whereClause = " WHERE TimeStamp BETWEEN :startDate AND :endDate";
    $params = [
        ':startDate' => strtotime($startDate),
        ':endDate' => strtotime($endDate)
    ];

    $query = "SELECT $selectCols FROM `$tableName`" . $whereClause . " ORDER BY TimeStamp DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $dados = $stmt->fetchAll();

    // Formatar TimeStamp para leitura, se existir na seleção
    if (in_array('TimeStamp', $colsFiltered)) {
        foreach ($dados as &$row) {
            if (isset($row['TimeStamp'])) {
                $row['TimeStamp'] = date('d-m-Y H:i:s', $row['TimeStamp']);
            }
        }
        unset($row);
    }

    if (isset($_GET['export'])) {
        if ($_GET['export'] === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="dados.csv"');
            $output = fopen('php://output', 'w');

            // Cabeçalho CSV
            fputcsv($output, $colsFiltered);

            // Dados CSV
            foreach ($dados as $row) {
                $linha = [];
                foreach ($colsFiltered as $col) {
                    $linha[] = $row[$col] ?? '';
                }
                fputcsv($output, $linha);
            }

            fclose($output);
            exit;
        }

        if ($_GET['export'] === 'xml') {
            header('Content-Type: application/xml; charset=utf-8');
            header('Content-Disposition: attachment; filename="dados.xml"');

            $xml = new SimpleXMLElement('<dados/>');

            foreach ($dados as $row) {
                $item = $xml->addChild('registro');
                foreach ($colsFiltered as $col) {
                    $item->addChild($col, htmlspecialchars($row[$col] ?? ''));
                }
            }

            echo $xml->asXML();
            exit;
        }
    }

    header('Content-Type: application/json');
    echo json_encode([
        "data" => $dados
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro de banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro: ' . $e->getMessage()]);
}
