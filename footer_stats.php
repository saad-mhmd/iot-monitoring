<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

$statusCounts = [];

// Fetch counts for each status
foreach (['online', 'offline', 'malfunction'] as $status) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM modules WHERE status = ?");
    $stmt->execute([$status]);
    $statusCounts[$status] = $stmt->fetchColumn();
}

// Fetch total number of modules
$stmt = $pdo->query("SELECT COUNT(*) FROM modules");
$statusCounts['total'] = $stmt->fetchColumn();

// Return as JSON
header('Content-Type: application/json');
echo json_encode($statusCounts);
?>