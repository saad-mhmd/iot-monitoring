<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

// Fetch all module IDs
$modules_stmt = $pdo->query('SELECT id FROM modules');
$module_ids = $modules_stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($module_ids as $module_id) {
    // Generate random value
    $value = rand(0, 100);
    $timestamp = date('Y-m-d H:i:s');

    // Insert data into module_data table
    $stmt = $pdo->prepare('INSERT INTO module_data (module_id, value, timestamp) VALUES (?, ?, ?)');
    $stmt->execute([$module_id, $value, $timestamp]);
}
?>
