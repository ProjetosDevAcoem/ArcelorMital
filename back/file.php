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

    // Obter tabelas que não terminam com "_AQI"
    $stmt = $pdo->prepare("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = :database
    AND table_name not LIKE '%_iqa'
    ");

    $stmt->execute(['database' => $config['db_name']]);
    $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Colunas esperadas na tabela
    $colunas_necessarias = ['TimeStamp','Tag','temp','umid','press','vel','chuva','rad','dir','Latitude','Longitude'];
    
    $dados = [];
    $timestampParametro = $_GET['timestamp'] ?? null;

    // Verificar se o timestamp é válido antes de processar as tabelas
    if ($timestampParametro !== null && !is_numeric($timestampParametro)) {
        throw new Exception('Timestamp inválido');
    }

    foreach ($tabelas as $tabela) {
        // Verificar se a tabela contém todas as colunas necessárias
        $placeholders = implode("','", $colunas_necessarias);
        $stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT column_name) 
            FROM information_schema.columns 
            WHERE table_schema = :database 
              AND table_name = :table 
              AND column_name IN ('$placeholders')
        ");
        $stmt->execute(['database' => 'acoempoa', 'table' => $tabela]);
        $colunas_encontradas = $stmt->fetchColumn() ?: 0;

        if ($colunas_encontradas == count($colunas_necessarias)) {
            // Montar a query de forma segura
            $query = "
                SELECT " . implode(', ', $colunas_necessarias) . " 
                FROM `$tabela`
            ";
            if ($timestampParametro !== null) {
                $query .= " WHERE TimeStamp = :timestamp";
            }
            $query .= " ORDER BY TimeStamp DESC LIMIT 1";

            $stmt = $pdo->prepare($query);
            $params = ($timestampParametro !== null) ? ['timestamp' => $timestampParametro] : [];
            $stmt->execute($params);

            // Adicionar resultado se houver dados
            if ($resultado = $stmt->fetch()) {
                $resultado['TableName'] = $tabela;
                $dados[] = $resultado;
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
