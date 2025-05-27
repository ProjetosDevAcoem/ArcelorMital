<?php
try {
    $config = include('../config.php');
    
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

    // Obter todas as tabelas
    $stmt = $pdo->prepare("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = :database
    ");
    $stmt->execute(['database' => 'acoempoa']);
    $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Definir colunas necessárias para consulta
    $colunas_necessarias = ['TimeStamp', 'Tag', 'Latitude', 'Longitude', 'iqa_final', 'iqa_pm10', 'iqa_pm25', 'iqa_o3', 'iqa_co', 'iqa_no2', 'iqa_so2'];

    $dados = [];
    $startDateParametro = $_GET['startDate'] ?? null;
    $endDateParametro = $_GET['endDate'] ?? null;
    $timestampParametro = $_GET['timestamp'] ?? null;

    // Verificar se os parâmetros de data são válidos
    if ($startDateParametro && !strtotime($startDateParametro)) {
        throw new Exception('Start date inválida');
    }

    if ($endDateParametro && !strtotime($endDateParametro)) {
        throw new Exception('End date inválida');
    }

    // Converter startDate e endDate para timestamps (em segundos)
    $startDateTimestamp = $startDateParametro ? (new DateTime($startDateParametro))->getTimestamp() : null;
    $endDateTimestamp = $endDateParametro ? (new DateTime($endDateParametro))->getTimestamp() : null;

    foreach ($tabelas as $tabela) {
        // Verificar se a tabela contém todas as colunas necessárias
        $queryVerificaColunas = "
            SELECT COUNT(*) 
            FROM information_schema.columns 
            WHERE table_schema = :database 
            AND table_name = :table 
            AND column_name IN ('" . implode("', '", $colunas_necessarias) . "')
        ";
        $stmt = $pdo->prepare($queryVerificaColunas);
        $stmt->execute(['database' => 'acoempoa', 'table' => $tabela]);
        $colunas_encontradas = $stmt->fetchColumn();

        if ($colunas_encontradas == count($colunas_necessarias)) {
            // Criar consulta segura para obter os registros no intervalo de datas
            $query = "SELECT " . implode(', ', $colunas_necessarias) . " FROM `$tabela`";
            $queryParams = [];

            // Adicionar filtros de data à consulta
            if ($timestampParametro !== null) {
                $query .= " WHERE TimeStamp = :timestamp";
                $queryParams['timestamp'] = $timestampParametro;
            } else {
                if ($startDateTimestamp !== null) {
                    $query .= " WHERE TimeStamp >= :startDate";
                    $queryParams['startDate'] = $startDateTimestamp;
                }
                if ($endDateTimestamp !== null) {
                    $query .= $startDateTimestamp ? " AND TimeStamp <= :endDate" : " WHERE TimeStamp <= :endDate";
                    $queryParams['endDate'] = $endDateTimestamp;
                }
            }

            $query .= " ORDER BY TimeStamp ASC"; // Agora pega os registros ordenados pela data

            $stmt = $pdo->prepare($query);
            $stmt->execute($queryParams);

            // Adicionar resultado se houver dados
            $resultados = $stmt->fetchAll();
            if (!empty($resultados)) {
                foreach ($resultados as &$resultado) {
                    $resultado['SerialNumber'] = $tabela; // Adiciona o nome da tabela em cada resultado
                }
                $dados[$tabela] = $resultados;
            }
        }
    }

    // Retornar os dados em JSON
    header('Content-Type: application/json');
    echo json_encode($dados, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro de banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro: ' . $e->getMessage()]);
}

?>
