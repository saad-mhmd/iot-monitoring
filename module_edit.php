<?php
require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

$id = $_GET['id']; // Get the module ID from the URL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];

    // Update module data in the database
    $updateStmt = $pdo->prepare('UPDATE modules SET name = ?, type = ? WHERE id = ?');
    $updateStmt->execute([$name, $type, $id]);

    // Redirect back to the dashboard
    header('Location: index.php');
    exit;
} else {
    // Fetch module details for pre-filling the form
    $stmt = $pdo->prepare('SELECT * FROM modules WHERE id = ?');
    $stmt->execute([$id]);
    $module = $stmt->fetch(PDO::FETCH_ASSOC);

    // Render the edit form
    echo $twig->render('module_edit.twig', ['module' => $module]);
}
?>