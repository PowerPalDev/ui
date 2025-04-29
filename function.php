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
            //$clientId = 'powerpal_php_backend_1';
            $username = null;
            $password = null;
            $clean_session = true;
            $mqtt_version = MqttClient::MQTT_3_1_1;
    
            // Create MQTT connection settings
            $connectionSettings = (new ConnectionSettings)
                ->setUsername($username)
                ->setPassword($password)
                ->setConnectTimeout(connectTimeout: 10)
                ->setReconnectAutomatically(true)
                ->setMaxReconnectAttempts(5)
                ->setDelayBetweenReconnectAttempts(2000) // 2 seconds between reconnect attempts
                ->setKeepAliveInterval(5);

    if ($mqtt === null || $reconnect) {

        // Create MQTT client
        $mqtt = new MqttClient($server, $port);

        // Connect to the MQTT broker
        $mqtt->connect($connectionSettings);
    }
    return $mqtt;
}

class Channel{
    public $deviceId;
    public $channelBlue;
    public $channelGreen;
    public int $state;
    public $color;
    
    public function __construct($deviceId, $channelBlue,
     $channelGreen, $state, $color){
        $this->deviceId = $deviceId;
        $this->channelBlue = $channelBlue;
        $this->channelGreen = $channelGreen;
        $this->state = $state;
        $this->color = $color;
        $this->publishState();
    }

    public function setState($state, $color){
        if($state == $this->state && $color == $this->color){
            return;
        }
        $this->state = $state;
        $this->color = $color;
        $this->publishState();
    }

    public function getState(){
        return $this->state;
    }

    public function getStateName(){
        return $this->state == 1 ? "on" : "off";
    }

    public function publishState(){
        $topic = $this->composeTopic();
        if($this->color == "blue"){
            echo "deviceId: $topic this->channel: {$this->channelBlue} color: {$this->color} state: {$this->getStateName()} \n";
            mqtt()->publish($topic, "{$this->channelBlue}:state:{$this->getStateName()}");
            mqtt()->publish($topic, "{$this->channelGreen}:state:off");
        }else{
            echo "deviceId: $topic this->channel: {$this->channelGreen} color: {$this->color} state: {$this->getStateName()} \n";
            mqtt()->publish($topic, "{$this->channelGreen}:state:{$this->getStateName()}");
            mqtt()->publish($topic, "{$this->channelBlue}:state:off");
        }
    }
    
    public function composeTopic(){
        return "/user/roy/{$this->deviceId}";
    }
}