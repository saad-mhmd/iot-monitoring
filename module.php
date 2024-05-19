<?php
require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM modules WHERE id = ?');
$stmt->execute([$id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

$data_stmt = $pdo->prepare('SELECT timestamp, value FROM module_data WHERE module_id = ? ORDER BY timestamp ASC');
$data_stmt->execute([$id]);
$data = $data_stmt->fetchAll(PDO::FETCH_ASSOC);

$timestamps = json_encode(array_column($data, 'timestamp'));
$values = json_encode(array_column($data, 'value'));

echo $twig->render('module.twig', [
    'module' => $module,
    'timestamps' => $timestamps,
    'values' => $values
]);
?>



<!-- module.php: This file handles the individual module view. It retrieves data for a specific module
(based on the id parameter) from both modules and module_data tables. It then renders the module.twig
template, which displays a line chart visualizing the module's data over time. -->