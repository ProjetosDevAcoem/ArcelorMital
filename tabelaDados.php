<?php
try {
    $config = include('config.php');

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

    // Pegar os parâmetros da URL
    $station = $_GET['station'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';

    if (empty($station)) {
        throw new Exception("Nenhuma estação informada.");
    }

    // Mapeamento das tabelas
    $stationsMap = [
        "station1" => "São Carlos",
        "station2" => "Restinga",
        "station3" => "AMRIGS",
        "station4" => "Unidade Móvel",
        "station5" => "Moacyr Scliar",
        "station6" => "Rodoviária"
    ];

    $stationsTableMap = [
        "station1" => "saocarlos",
        "station2" => "restinga",
        "station3" => "amrigs",
        "station4" => "emqarmovel",
        "station5" => "moacyrscliar",
        "station6" => "rodoviaria"
    ];

    if (!isset($stationsTableMap[$station])) {
        throw new Exception("Estação inválida.");
    }

    $tableName = $stationsTableMap[$station];
    $stationName = $stationsMap[$station];

    // 🔥 Se as datas forem fornecidas, convertê-las para timestamps
    $whereClause = "";
    $params = [];

    if (!empty($startDate) && !empty($endDate)) {
        $whereClause = " WHERE TimeStamp BETWEEN :startDate AND :endDate";
        $params[':startDate'] = strtotime($startDate . ' +3 hours');
        $params[':endDate'] = strtotime($endDate . ' +3 hours');

    }

    // Buscar os dados filtrados
    $query = "SELECT TimeStamp, Tag, temp, umid, press, vel, chuva, rad, dir FROM `$tableName`" . $whereClause . " ORDER BY TimeStamp DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $dados = $stmt->fetchAll();

    // Retornar os dados em JSON, incluindo o nome da estação
    header('Content-Type: application/json');
    echo json_encode([
        "station_name" => $stationName,
        "data" => $dados
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro de banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro: ' . $e->getMessage()]);
}
?>
