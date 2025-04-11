<?php
require_once 'function.php';
header('Content-Type: application/json');

$db = DB();
// Query to get all channels
$query = "SELECT * FROM channel";
$result = $db->query($query);

if (!$result) {
    die(json_encode(['error' => 'Query failed: ' . $db->error]));
}

$response = [];

// Process results and build response structure
while ($row = $result->fetch_assoc()) {
    $deviceId = $row['deviceId']; // Convert device ID to hex format
    
    if (!isset($response[$deviceId])) {
        $response[$deviceId] = [];
    }
    
    // Check if device is offline (more than 5 minutes since last update)
    $offlineStatus = false;
    if (time() - $row['lastUpdate'] > 300) { // 5 minutes = 300 seconds
        $timeDiff = time() - $row['lastUpdate'];
        $hours = floor($timeDiff / 3600);
        $minutes = floor(($timeDiff % 3600) / 60);
        $offlineStatus = sprintf("%d:%02d", $hours, $minutes);
    }

    if ($row['state'] == 1) {
        $colorString = $row['color'];
    } else {
        $colorString = 'grey';
    }

    if($row['isDimmable'] == 1) {
        $duty = $row['duty'];
    } else {
        $duty = 0;
    }

    if($row['isThermostat'] == 1) {
        $currentTemp = $row['duty'];
        $targetTemp = $row['targetTemp'];
    } else {
        $currentTemp = 0;
        $targetTemp = 0;
    }
    
    $response[$deviceId][$row['channel']] = [
        'offline' => $offlineStatus,
        'color' => $colorString,
        'power' => (float)$row['power'], // Convert to same format as original
        'voltage' => (float)$row['voltage'], // Convert to same format as original
        'state' => $row['state'] ? 'on' : 'off',
        'isDimmable' => $row['isDimmable'],
        'duty' => $duty,
        'isThermostat' => $row['isThermostat'],
        'currentTemp' => $currentTemp,
        'targetTemp' => $targetTemp,
    ];
}

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);

// Close database connection
$db->close();