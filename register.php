<?php
require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $status = 'online';
    $value = 0;
    $operating_time = 0;

    $stmt = $pdo->prepare('INSERT INTO modules (name, type, status, value, operating_time) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $type, $status, $value, $operating_time]);

    header('Location: index.php');
    exit;
}

echo $twig->render('register.twig');
?>
