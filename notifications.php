<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

// Check for modules in "malfunction" state
$stmt = $pdo->query("SELECT COUNT(*) FROM modules WHERE status = 'malfunction'");
$malfunctionCount = $stmt->fetchColumn();

if ($malfunctionCount > 0) {
    $message = "Warning: $malfunctionCount module(s) are malfunctioning!";
    echo json_encode(['message' => $message]); // Return as JSON
} else {
    echo json_encode(['message' => '']); // No malfunctions
}
?>