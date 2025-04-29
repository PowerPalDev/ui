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

$query = "SELECT * FROM channel";
$result = $db->query($query);
$channels = [];

foreach($result as $row){
    print_r($row);
    $channels[$row['deviceId']][$row['channel']] = new Channel(
        $row['deviceId'], 
        $row['channel'],
        $row['greenChannel'],
        $row['state'],
        $row['color']
    );
}

while (true) {
    sleep(1);
    $query = "SELECT * FROM channel";
    $result = $db->query($query);
    while ($row = $result->fetch_assoc()) {
        try {
            $deviceId = $row['deviceId'];
            $topic = "/user/roy/$deviceId";
            
            $channel = $channels[$deviceId][$row['channel']];
            $color = $row['color'];
            $state = $row['state'];


            $channel->setState($state, $color);
            
        } catch (Exception $e) {
            mqtt(true);
            error_log("MQTT error: " . $e->getMessage());
        }
    }
}
