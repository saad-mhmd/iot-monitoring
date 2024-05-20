<?php
require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

// Fetch module data and total data received for each module
$stmt = $pdo->query('
    SELECT m.*, COUNT(md.id) AS total_data
    FROM modules m
    LEFT JOIN module_data md ON m.id = md.module_id
    GROUP BY m.id
');
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo $twig->render('dashboard.twig', ['modules' => $modules]);
?>



<!-- index.php: This file serves as the main entry point of the application.
It retrieves data from the modules table in the MySQL database and renders
the dashboard.twig template, displaying a table of registered modules. -->