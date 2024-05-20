<?php
require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Get current status
    $stmt = $pdo->prepare('SELECT status FROM modules WHERE id = ?');
    $stmt->execute([$id]);
    $currentStatus = $stmt->fetchColumn();

    // Toggle status
    $newStatus = ($currentStatus == 'online') ? 'offline' : 'online';

    // Update status in the database
    $updateStmt = $pdo->prepare('UPDATE modules SET status = ? WHERE id = ?');
    $updateStmt->execute([$newStatus, $id]);

    // Redirect back to the dashboard
    header('Location: index.php');
    exit;
}
?>