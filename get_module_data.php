<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

$moduleId = $_GET['id'];
$timeRange = isset($_GET['time_range']) ? $_GET['time_range'] : 'all'; 

// Prepare the SQL query (same as in module.php)
$dataStmt = $pdo->prepare("
    SELECT timestamp, value 
    FROM module_data 
    WHERE module_id = :moduleId
    AND 
    CASE 
        WHEN :timeRange = '5min' THEN timestamp >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        WHEN :timeRange = '30min' THEN timestamp >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
        WHEN :timeRange = '1hour' THEN timestamp >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
        WHEN :timeRange = '24hour' THEN timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ELSE TRUE  -- No time filter for 'all'
    END
    ORDER BY timestamp ASC
");

$dataStmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
$dataStmt->bindParam(':timeRange', $timeRange, PDO::PARAM_STR);
$dataStmt->execute(); 

$data = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data); 
?>