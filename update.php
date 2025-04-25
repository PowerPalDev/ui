<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'function.php';

$db = DB();


// Get parameters from request
$deviceId = $_GET['deviceId'];
$channel = $_GET['channel'];
$state = null;
$duty = null;
$temperature = isset($_GET['temperature']) ? $_GET['temperature'] : null;

// Prepare the base update SQL
$updateFields = [];
$updateValues = [];
$currentTime = time();

if (isset($_GET['state'])) {
    $state = $_GET['state'];
    if ($state == 'on') {
        $duty = 100;
    } else {
        $duty = 0;
    }
}

if (isset($_GET['duty'])) {
    $duty = $_GET['duty'];
    if ($duty > 100) {
        $duty = 100;
    }
    if ($duty < 0) {
        $duty = 0;
    }

    if ($duty == 0) {
        $state = 'off';
    } else {
        $state = 'on';
    }

}

// Add fields based on what was received
if (isset($_GET['state'])) {  
    $message = "$channel:state:$state";
    mqtt()->publish("/user/roy/$deviceId", $message);
}

if (isset($_GET['duty'])) {
    $message = "$channel:pwm:" . ($duty * 10);
    mqtt()->publish("/user/roy/$deviceId", $message);
}

if($duty) {
    $updateFields[] = "duty = ?";
    $updateValues[] = $duty;  // Convert to same scale as stored in DB
}

if($state) {
    $updateFields[] = "state = ?";
    $updateValues[] = ($state === 'on') ? 1 : 0;
}


if ($temperature !== null) {
    //the device is not really a thermostat, so we only update the db
    $updateFields[] = "targetTemp = ?";
    $updateValues[] = $temperature;
    
    //$message = "$channel:temperature:$temperature";
    //$mqtt->publish("/user/roy/$deviceId", $message);
}

// Always update lastUpdate time
$updateFields[] = "lastUpdate = ?";
$updateValues[] = $currentTime;

if (count($updateFields) > 0) {
    // Construct the SQL query
    $sql = "UPDATE channel SET " . implode(", ", $updateFields) . 
           " WHERE deviceId = ? AND channel = ?";
    
    // Add deviceId and channel to values array
    $updateValues[] = $deviceId;
    $updateValues[] = $channel;
    
    // Prepare and execute the statement
    $stmt = $db->prepare($sql);
    
    if ($stmt) {
        // Create type string for bind_param
        $types = str_repeat('s', count($updateValues));
        $stmt->bind_param($types, ...$updateValues);
        
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $response = [
                'success' => true,
                'message' => "Updated device $deviceId channel $channel",
                'updates' => [
                    'state' => $state,
                    'duty' => $duty,
                    'temperature' => $temperature
                ]
            ];
        } else {
            $response = [
                'success' => false,
                'message' => "No matching record found for device $deviceId channel $channel"
            ];
        }
        
        $stmt->close();
    } else {
        $response = [
            'success' => false,
            'message' => "Database error: " . $db->error
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => "No update parameters provided"
    ];
}

// Close connections
mqtt()->disconnect();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);