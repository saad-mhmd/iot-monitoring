<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

if (isset($_GET['id'])) {
    $moduleId = $_GET['id'];

    // Delete related data from module_data first (to avoid foreign key constraints)
    $deleteDataStmt = $pdo->prepare('DELETE FROM module_data WHERE module_id = ?');
    $deleteDataStmt->execute([$moduleId]);

    // Delete the module from the modules table
    $deleteModuleStmt = $pdo->prepare('DELETE FROM modules WHERE id = ?');
    $deleteModuleStmt->execute([$moduleId]);

    // Redirect back to the dashboard 
    header('Location: index.php');
    exit;
} else {
    echo "Module ID not provided.";
}
?>