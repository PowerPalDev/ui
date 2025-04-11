<?php
// Import the PHP MQTT Client library
// Note: You need to install this library using Composer:
// composer require php-mqtt/client

require_once __DIR__ . '/vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// MQTT connection parameters
$server = 'seisho.us';
$port = 1883;
$clientId = 'powerpal_php_backend_1';
$username = null; // Set if your MQTT broker requires authentication
$password = null; // Set if your MQTT broker requires authentication
$clean_session = true;
$mqtt_version = MqttClient::MQTT_3_1_1;

// Create connection settings
$connectionSettings = (new ConnectionSettings)
    ->setUsername($username)
    ->setPassword($password)
    ->setKeepAliveInterval(60);

// Create MQTT client
$mqtt = new MqttClient($server, $port, $clientId);

// Connect to the MQTT broker with the connection settings
$mqtt->connect($connectionSettings);

//127.0.0.1/ui/update.php?deviceId=${deviceId}&channel=${channel}&state=${state}
$deviceId = $_GET['deviceId'];
$channel = $_GET['channel'];
$state = $_GET['state'];
$topic = "/user/roy/$deviceId";
$message = "$channel:state:$state";
$mqtt->publish($topic, $message);

echo "Device $deviceId channel $channel turned $state";