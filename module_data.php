<?php
$pdo = new PDO('mysql:host=localhost;dbname=iot', 'root', '');

// Fetch all module IDs
$modules_stmt = $pdo->query('SELECT id FROM modules WHERE status = "online"');
$module_ids = $modules_stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($module_ids as $module_id) {
    $value = rand(0, 100);
    $timestamp = date('Y-m-d H:i:s');

    $data_stmt = $pdo->prepare('INSERT INTO module_data (module_id, value, timestamp) VALUES (?, ?, ?)');
    $data_stmt->execute([$module_id, $value, $timestamp]);



    // Calculate operating time
    $firstTimestampStmt = $pdo->prepare("SELECT MIN(timestamp) FROM module_data WHERE module_id = ?");
    $firstTimestampStmt->execute([$module_id]);
    $firstTimestamp = $firstTimestampStmt->fetchColumn();

    if ($firstTimestamp) {
        $currentTime = new DateTime(); // Current time
        $firstDataTime = new DateTime($firstTimestamp); // Time of the first data point
        $operatingTime = $currentTime->diff($firstDataTime);
        $operatingTimeSeconds = ($operatingTime->days * 86400) + 
                                ($operatingTime->h * 3600) + 
                                ($operatingTime->i * 60) + 
                                $operatingTime->s; 


    $update_stmt = $pdo->prepare('UPDATE modules SET value = ?, operating_time = ? WHERE id = ?');
    $update_stmt->execute([$value, $operatingTimeSeconds, $module_id]);
    }
}

// Set the default refresh interval to 5 seconds if not specified in the URL
$refreshInterval = isset($_GET['refresh']) ? (int)$_GET['refresh'] : 5; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Module Data Update</title>
    <script>

        let timeLeft = <?php echo $refreshInterval; ?>; // Initialize with PHP value

        function countdown() {
            const timerElement = document.getElementById("timer");
            timerElement.innerHTML = "Data will refresh in: " + timeLeft + " seconds";
            timeLeft -= 1; 

            if (timeLeft < 0) {
                timeLeft = <?php echo $refreshInterval; ?>; // Reset the timer 
            }
        }

        // Call countdown() every second
        setInterval(countdown, 1000);

        function setRefreshInterval(seconds) {
            // Redirect to the same page with the new refresh interval
            window.location.href = "module_data.php?refresh=" + seconds;
        }
    </script>
</head>
<body>
    <h1>Data Update Frequency</h1>

    <div id="timer"></div>

    <button onclick="setRefreshInterval(5)">5 Seconds</button>
    <button onclick="setRefreshInterval(10)">10 Seconds</button>
    <button onclick="setRefreshInterval(30)">30 Seconds</button>
    <button onclick="setRefreshInterval(60)">1 Minute</button>
    <button onclick="setRefreshInterval(300)">5 Minutes</button>
    <button onclick="setRefreshInterval(600)">10 Minutes</button>
    <button onclick="setRefreshInterval(1800)">30 Minutes</button>
    <button onclick="setRefreshInterval(3600)">1 Hour</button>

    <meta http-equiv="refresh" content="<?php echo $refreshInterval; ?>; url=module_data.php?refresh=<?php echo $refreshInterval; ?>"> 
</body>
</html>

<!-- This file generates dummy data for the registered modules and inserts it into the module_data table.
This simulates real-time data from actual IoT devices. -->
