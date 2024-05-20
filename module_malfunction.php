<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

// Function to simulate malfunction/online status changes
function updateModuleStatus($pdo) {
    $modulesStmt = $pdo->query('SELECT id, status FROM modules');
    $modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($modules as $module) {
        $randomChance = rand(1, 100); // Adjust probability as needed 

        if ($module['status'] == 'online' && $randomChance <= 10) {  // 10% chance of malfunction
            $newStatus = 'malfunction';
        } elseif ($module['status'] == 'malfunction' && $randomChance <= 5) { // 5% chance of going back online
            $newStatus = 'online';
        } else {
            continue; // No status change
        }

        $updateStmt = $pdo->prepare('UPDATE modules SET status = ? WHERE id = ?');
        $updateStmt->execute([$newStatus, $module['id']]);
    }
}

// --- Call the status update function ---
updateModuleStatus($pdo);

// Fetch updated module data INCLUDING total_data 
$stmt = $pdo->query('
    SELECT m.*, COUNT(md.id) AS total_data
    FROM modules m
    LEFT JOIN module_data md ON m.id = md.module_id
    GROUP BY m.id
');
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return updated module data as JSON
header('Content-Type: application/json');
echo json_encode($modules);
?>