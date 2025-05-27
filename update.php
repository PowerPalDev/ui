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

//read from the db the current state of this row

$channel = Channel::load($deviceId, $channel);
$channel->noMqttSync = true;

// Prepare the base update SQL
$updateFields = [];
$updateValues = [];
$currentTime = time();



if (isset($_GET['state'])) {
    $state = $_GET['state'];
    if ($state == 'on') {
        if($deviceId == "AC1518D6640C"){
            //Special logic, when the wind turbine is on the GREEN CHANNEL is FORCED ON
            setColor('green');
        }
        $channel->setDuty(100);
        $channel->setState(1);
    } else {
        if($deviceId == "AC1518D6640C"){
            //Special logic, when the wind turbine is on the GREEN CHANNEL is FORCED ON
            setColor('blue');
        }
        $channel->setDuty(0);
        $channel->setState(0);
    }
}

if (isset($_GET['duty'])) {
    $duty = $_GET['duty'];
    echo "duty: $duty\n";
    if ($duty > 100) {
        $duty = 100;
    }
    if ($duty < 0) {
        $duty = 0;
    }

    if ($duty == 0) {
        $channel->setState(0);
        if($deviceId == "AC1518D6640C"){
            //Special logic, when the wind turbine is on the GREEN CHANNEL is FORCED ON
            setColor('blue');
        }
    }else{
        if($deviceId == "AC1518D6640C"){
            $limit = (int)($duty / 10);
            //Special logic, when the wind turbine is on the GREEN CHANNEL is FORCED ON, but only on some devices
            setColor('green', $limit);
        }
    }
    

    $channel->setDuty($duty);
}

function setColor($color, $colorCount = null){
    $limit = "";
    if($colorCount != null){
        $limit = " LIMIT $colorCount";
    }
    $sql = "UPDATE channel SET color = '$color' ORDER BY RAND() $limit";
    echo "setColor: $sql";
    DB()->query($sql);
}