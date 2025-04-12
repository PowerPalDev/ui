<?php

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

function DB()
{
    static $db;
    if ($db === null) {
        // Database connection
        $db = new mysqli('127.0.0.1', 'roy', 'roy', 'dsTest1');

        if ($db->connect_error) {
            die(json_encode(['error' => 'Database connection failed: ' . $db->connect_error]));
        }
    }
    return $db;
}

function mqtt($reconnect = false)
{
    static $mqtt;
            // MQTT connection parameters
            $server = 'seisho.us';
            $port = 1883;
            $clientId = 'powerpal_php_backend_1';
            $username = null;
            $password = null;
            $clean_session = true;
            $mqtt_version = MqttClient::MQTT_3_1_1;
    
            // Create MQTT connection settings
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($username)
                ->setPassword($password)
                ->setKeepAliveInterval(60);

    if ($mqtt === null || $reconnect) {

        // Create MQTT client
        $mqtt = new MqttClient($server, $port, $clientId);

        // Connect to the MQTT broker
        $mqtt->connect($connectionSettings);
    }
    return $mqtt;
}
