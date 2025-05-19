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

// Prepare the base update SQL
$updateFields = [];
$updateValues = [];
$currentTime = time();

if (isset($_GET['state'])) {
    $state = $_GET['state'];
    if ($state == 'on') {
        $channel->setDuty(100);
        $channel->setState(1);
    } else {
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
    }

    $channel->setDuty($duty);
}