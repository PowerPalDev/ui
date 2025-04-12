<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'function.php';
$db = DB();

function heatingOn(&$row)
{
    //the more the difference the more we increase
    $difference = $row['targetTemp'] - $row['currentTemp'];
    $pwm = $difference * 20;
    if ($pwm > 100) {
        $pwm = 100;
    }
    if ($pwm < 20) {
        $pwm = 20;
    }
    
    $row['currentTemp'] = $row['currentTemp'] + ($pwm * 0.001);
    $row['power'] = $pwm * 30;
    return $pwm;
}

function coolingOn(&$row)
{
    //we just decrease each time
    $row['currentTemp'] = $row['currentTemp'] - 0.03;
    return $row;
}

while (true) {
    sleep(1);
    $query = "SELECT * FROM channel";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        try {
            if ($row['isThermostat'] == 1) {
                $deviceId = $row['deviceId'];
                $topic = "/user/roy/$deviceId";
                $channel = $row['channel'];
                $color = $row['color'];
                $state = $row['state'];
                switch ($color) {
                    case 'blue':
                        $mqttChannel = $row['channel'];
                        mqtt()->publish($topic, $message = "{$row['greenChannel']}:state:off");
                        mqtt()->publish($topic, $message = "{$row['violetChannel']}:state:off");
                        break;
                    case 'green':
                        $mqttChannel = $row['greenChannel'];
                        mqtt()->publish($topic, $message = "{$row['channel']}:state:off");
                        mqtt()->publish($topic, $message = "{$row['violetChannel']}:state:off");
                        break;
                    case 'violet':
                        $mqttChannel = $row['violetChannel'];
                        mqtt()->publish($topic, $message = "{$row['channel']}:state:off");
                        mqtt()->publish($topic, $message = "{$row['greenChannel']}:state:off");
                        break;
                }

                $targetTemp = $row['targetTemp'];
                $currentTemp = $row['currentTemp'];
                
                // Add hysteresis offset based on current state to avoid bang bang control
                if ($state == 1) { // If currently heating
                    if ($currentTemp < ($targetTemp + 0.3)) {
                        $pwm = heatingOn($row) * 10;
                        $message = "$mqttChannel:pwm:$pwm";
                        $state = 1;  // PWM > 0 means heating is on
                    } else {
                        coolingOn($row);
                        $message = "$mqttChannel:state:off";
                        $state = 0;  // Cooling means state is off
                        $row['power'] = 0;
                    }
                } else { // If currently cooling or off
                    if ($currentTemp < ($targetTemp - 0.3)) {
                        $pwm = heatingOn($row) * 10;
                        $message = "$mqttChannel:pwm:$pwm";
                        $state = 1;  // PWM > 0 means heating is on
                    } else {
                        coolingOn($row);
                        $message = "$mqttChannel:state:off";
                        $state = 0;  // Cooling means state is off
                        $row['power'] = 0;
                    }
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

                mqtt()->publish($topic, $message);
            }
        } catch (Exception $e) {
            mqtt(true);
            error_log("MQTT error: " . $e->getMessage());
        }
    }
}
