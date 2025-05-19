<?php

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

require_once 'config.php';

function DB()
{
    static $db;
    if ($db === null) {
        // Database connection
	 $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
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
            $server = MQTT_SERVER;
            $port = MQTT_PORT;
            $clientId = 'powerpal_php_backend_1' . uniqid();
            $username = MQTT_USER;
            $password = MQTT_PASS;
            $clean_session = false;
            $mqtt_version = MqttClient::MQTT_3_1_1;
    
            // Create MQTT connection settings
        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setConnectTimeout(connectTimeout: 10)
            ->setReconnectAutomatically(false) // Disable auto reconnect since it conflicts with clean_session
            ->setKeepAliveInterval(5);

    if ($mqtt === null || $reconnect) {

        // Create MQTT client
        $mqtt = new MqttClient($server, $port);

        // Connect to the MQTT broker
        $mqtt->connect($connectionSettings, $clientId);
    }
    return $mqtt;
}

class Channel{
    public $deviceId;
    public $channelBlue;
    public $channelGreen;
    public int $state; 
    public $color;
    public $duty;
    public $currentTemp;
    public $targetTemp;
    
    public function __construct($deviceId, $channelBlue,
     $channelGreen, $state, $color, $duty, $currentTemp, $targetTemp){
        $this->deviceId = $deviceId;
        $this->channelBlue = $channelBlue;
        $this->channelGreen = $channelGreen;
        $this->state = $state;
        $this->color = $color;
        $this->duty = $duty;
        $this->currentTemp = $currentTemp;
        $this->targetTemp = $targetTemp;
    }

    static function load($deviceId, $channel){
        $db = DB();
        $query = "SELECT * FROM channel WHERE deviceId = ? AND channel = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $deviceId, $channel);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $channel = new Channel(
            $row['deviceId'], 
            $row['channel'],
            $row['greenChannel'],
            $row['state'],
            $row['color'],
            $row['duty'],
            $row['currentTemp'],
            $row['targetTemp']
        );

        return $channel;
    }

    public function setDuty($duty){
        if($duty == $this->duty){
            return;
        }
        $this->duty = $duty;
        $this->publishDuty();
        $this->updateDB();
    }

    public function setTemperature($temperature){
        if($temperature == $this->temperature){
            return;
        }
        $this->temperature = $temperature;
        $this->updateDB();
    }

    public function getDuty(){
        return $this->duty;
    }

    public function setState($state, $color = null){
        if($state == $this->state && $color == $this->color){
            return;
        }
        $this->state = $state;
        if($color != null){
            $this->color = $color;
        }
        $this->publishState();
        $this->updateDB();
    }

    public function getState(){
        return $this->state;
    }

    public function getStateName(){
        return $this->state == 1 ? "on" : "off";
    }

    public function updateDB(){
        $db = DB();
        $query = <<<EOD
UPDATE channel SET 
    state = ?, 
    duty = ?, 
    currentTemp = ?, 
    targetTemp = ?, 
    lastUpdate = ? 
WHERE deviceId = ? AND channel = ?
EOD;

        $stmt = $db->prepare($query);
        $timestamp = time();
        $stmt->bind_param('iiisiss',
         $this->state,
          $this->duty,
           $this->currentTemp,
            $this->targetTemp,
             $timestamp,
              $this->deviceId,
               $this->channel);
        $stmt->execute();
    }

    public function getChannel($color = null){
        if($color == "blue"){
            return $this->channelBlue;
        }else if($color == "green"){
            return $this->channelGreen;
        }else{
            return $this->getChannel($this->color);
        }
    }

    public function getOtherChannel($color = null){
        if($color == "blue"){
            return $this->channelGreen;
        }else{
            return $this->channelBlue;
        }
    }

    public function switchChannel($channel, $state){
        
    }

    public function publishDuty(){

        $topic = $this->composeTopic();
        $msg = "{$this->getChannel()}:duty:{$this->duty}";
        mqtt()->publish($topic, $msg);
    }

    public function publishState(){
        $topic = $this->composeTopic();
        if($this->color == "blue"){
            echo "deviceId: $topic this->channel: {$this->channelBlue} color: {$this->color} state: {$this->getStateName()} \n";
            $msg1 = "{$this->channelBlue}:state:{$this->getStateName()}";
            $msg2 = "{$this->channelGreen}:state:off";
            echo "msg1: $msg1 \n";
            echo "msg2: $msg2 \n";
            mqtt()->publish($topic, $msg1);
            mqtt()->publish($topic, $msg2);
        }else{
            echo "deviceId: $topic this->channel: {$this->channelBlue} color: {$this->color} state: {$this->getStateName()} \n";
            $msg1 = "{$this->channelGreen}:state:{$this->getStateName()}";
            $msg2 = "{$this->channelBlue}:state:off";
            echo "msg1: $msg1 \n";
            echo "msg2: $msg2 \n";
            mqtt()->publish($topic, $msg1);
            mqtt()->publish($topic, $msg2);
        }
    }
    
    public function composeTopic(){
        return "/user/roy/{$this->deviceId}";
    }
}