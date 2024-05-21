<?php
require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM modules WHERE id = ?');
$stmt->execute([$id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

// Get time range from URL parameter or default to 'all'
$timeRange = isset($_GET['time_range']) ? $_GET['time_range'] : '1hour'; 

// Adjust SQL query to filter data based on time range
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

// Bind the parameters
$dataStmt->bindParam(':moduleId', $id, PDO::PARAM_INT);
$dataStmt->bindParam(':timeRange', $timeRange, PDO::PARAM_STR);
$dataStmt->execute(); // No need to pass an array to execute() now 

$data = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

$timestamps = json_encode(array_column($data, 'timestamp'));
$values = json_encode(array_column($data, 'value'));

echo $twig->render('module.twig', [
    'module' => $module,
    'timestamps' => $timestamps,
    'values' => $values,
    'timeRange' => $timeRange // Pass the timeRange to the template
]);
?>



<!-- module.php: This file handles the individual module view. It retrieves data for a specific module
(based on the id parameter) from both modules and module_data tables. It then renders the module.twig
template, which displays a line chart visualizing the module's data over time. -->