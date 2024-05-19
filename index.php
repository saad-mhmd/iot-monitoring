<?php
require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');
$stmt = $pdo->query('SELECT * FROM modules');
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('dashboard.twig', ['modules' => $modules]);
?>



<!-- index.php: This file serves as the main entry point of the application.
It retrieves data from the modules table in the MySQL database and renders
the dashboard.twig template, displaying a table of registered modules. -->