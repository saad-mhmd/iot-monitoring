<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the interval from the POST request
    $interval = (int)$_POST['interval']; 

    // Fetch all module IDs
    $modules_stmt = $pdo->query('SELECT id FROM modules');
    $module_ids = $modules_stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($module_ids as $module_id) {
        $value = rand(0, 100);
        $timestamp = date('Y-m-d H:i:s');

        $data_stmt = $pdo->prepare('INSERT INTO module_data (module_id, value, timestamp) VALUES (?, ?, ?)');
        $data_stmt->execute([$module_id, $value, $timestamp]);

        $update_stmt = $pdo->prepare('UPDATE modules SET value = ?, operating_time = operating_time + 1 WHERE id = ?');
        $update_stmt->execute([$value, $module_id]);
    }

    // Send a response back
    echo "Data updated!"; 
} else {
    // If not a POST request, display the form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Module Data Update</title>
    </head>
    <body>
        <h1>Update Interval</h1>
        <form method="POST" id="intervalForm">
            <label for="interval">Interval (seconds):</label>
            <input type="number" name="interval" id="interval" value="5" min="1">
            <button type="button" onclick="updateInterval()">Set Interval</button>
        </form>

        <script>
            function updateInterval() {
                const interval = document.getElementById('interval').value;
                const formData = new FormData();
                formData.append('interval', interval);

                fetch('module_data.php', { 
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Log the response from the server
                });
            }
        </script>
    </body>
    </html>
    <?php
}
?>


<!-- This file generates dummy data for the registered modules and inserts it into the module_data table.
This simulates real-time data from actual IoT devices. -->
