<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'function.php';
$db = DB();

function heatingOn(&$row){
    //the more the difference the more we increase
    $difference = $row['targetTemp'] - $row['currentTemp'];
    $pwm = $difference * 20;
    if($pwm > 100){
        $pwm = 100;
    }
    $row['currentTemp'] = $row['currentTemp'] + ($difference * 0.03);
    $row['power'] = $pwm * 30;
    return $pwm;
}

function coolingOn(&$row){
    //we just decrease each time
    $row['currentTemp'] = $row['currentTemp'] - 0.03;
    return $row;
}

while(true){
    sleep(1);
    $query = "SELECT * FROM channel";
    $result = $db->query($query);
    while($row = $result->fetch_assoc()){
        if($row['isThermostat'] == 1){
            $deviceId = $row['deviceId'];
            $channel = $row['channel'];
            $targetTemp = $row['targetTemp'];
            $currentTemp = $row['currentTemp'];

            if($currentTemp < $targetTemp){
                $pwm = heatingOn($row) * 10;
                $message = "$channel:pwm:$pwm";
                $state = 1;  // PWM > 0 means heating is on
            }else{
                coolingOn($row);
                $message = "$channel:state:off";
                $state = 0;  // Cooling means state is off
                $row['power'] = 0;
            }
            
            // Update the database with the new currentTemp, state and power
            $updateSql = "UPDATE channel SET currentTemp = ?, state = ?, power = ? WHERE deviceId = ? AND channel = ?";
            $stmt = $db->prepare($updateSql);
            
            if ($stmt) {
                $stmt->bind_param("disss", $row['currentTemp'], $state, $row['power'], $deviceId, $channel);
                $stmt->execute();
                $stmt->close();
            } else {
                // Log error if prepare statement fails
                error_log("Database error updating temperature, state and power: " . $db->error);
            }
            mqtt()->publish("/user/roy/$deviceId", $message);
        }
    }
}