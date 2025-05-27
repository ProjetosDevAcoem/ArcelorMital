<?php
try {
    $config = include('config.php');
    
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", // Adicionando charset=utf8mb4
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Ativar exceções para erros
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Buscar dados como array associativo
            PDO::ATTR_EMULATE_PREPARES => false // Melhor segurança contra SQL Injection
        ]
    );

    // Pegar o nome da estação da URL
    $station = $_GET['station'] ?? '';

    if (empty($station)) {
        throw new Exception("Nenhuma estação informada.");
    }

    // Mapear a estação para a tabela correta no banco
    $stationsMap = [
        "station1" => "saocarlos",
        "station2" => "restinga",
        "station3" => "amrigs",
        "station4" => "emqarmovel",
        "station5" => "moacyrscliar",
        "station6" => "rodoviaria"
    ];

    if (!isset($stationsMap[$station])) {
        throw new Exception("Estação inválida.");
    }

    $tableName = $stationsMap[$station];

    // Buscar os últimos dados da estação
    $query = "SELECT TimeStamp, temp, umid, press, vel, chuva, rad, dir FROM `$tableName` ORDER BY TimeStamp DESC LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $dados = $stmt->fetchAll();

    // Retornar os dados em JSON
    header('Content-Type: application/json');
    echo json_encode($dados, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro de banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro: ' . $e->getMessage()]);
}
?>
