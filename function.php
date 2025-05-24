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
    static $mqtt = null;
    if ($mqtt === null || $reconnect) {
        try {
            // MQTT connection parameters
            $server = MQTT_SERVER;
            $port = MQTT_PORT;
            $clientId = 'powerpal_php_backend_1' . uniqid();
            $clean_session = false;
            $mqtt_version = MqttClient::MQTT_3_1_1;

            // Log connection attempt
            error_log("Attempting MQTT connection to $server:$port with client ID: $clientId");

            // Create MQTT connection settings
            $connectionSettings = (new ConnectionSettings)
                ->setConnectTimeout(connectTimeout: 10)
                ->setReconnectAutomatically(false)
                ->setKeepAliveInterval(5)
                ->setLastWillTopic('/user/roy/status')
                ->setLastWillMessage('offline')
                ->setLastWillQualityOfService(1);


            // Create MQTT client
            $mqtt = new MqttClient($server, $port, $clientId, $mqtt_version);

            // Log client creation
            error_log("MQTT client created successfully");

            // Connect to the MQTT broker
            $state = $mqtt->connect($connectionSettings);

            if ($state === false) {
                error_log("MQTT connection failed for client ID: $clientId");
                throw new Exception("MQTT connection failed");
            }

            error_log("MQTT connected successfully. Client ID: $clientId");
        } catch (Exception $e) {
            error_log("MQTT connection error: " . $e->getMessage());
            throw $e;
        }
    }
    return $mqtt;
}

function publish($topic, $msg)
{
    
    try{
        mqtt()->publish($topic, $msg);
    } catch (Exception $e) {
        error_log("MQTT first publish error: " . $e->getMessage());
        //force reconnect
        mqtt(true);
        //an try to publish again   
        try{
            mqtt()->publish($topic, $msg);
        } catch (Exception $e) {
            error_log("MQTT second publish error: " . $e->getMessage());
        }
    }   
    
}

class Channel
{
    public $deviceId;
    public $channelBlue;
    public $channelGreen;
    public int $state;
    public $color;
    public $duty;
    public $currentTemp;
    public $targetTemp;
    public $noMqttSync = false;

    public function __construct(
        $deviceId,
        $channelBlue,
        $channelGreen,
        $state,
        $color,
        $duty,
        $currentTemp,
        $targetTemp
    ) {
        $this->deviceId = $deviceId;
        $this->channelBlue = $channelBlue;
        $this->channelGreen = $channelGreen;
        $this->state = $state;
        $this->color = $color;
        $this->duty = $duty;
        $this->currentTemp = $currentTemp;
        $this->targetTemp = $targetTemp;
    }

    static function fromRow($row)
    {
        return new Channel(
            $row['deviceId'],
            $row['channel'],
            $row['greenChannel'],
            $row['state'],
            $row['color'],
            $row['duty'],
            $row['currentTemp'],
            $row['targetTemp']
        );
    }

    static function load($deviceId, $channel)
    {
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

    public function setDuty($duty)
    {
        if ($duty == $this->duty) {
            return;
        }
        $this->duty = $duty;
        $this->publishDuty();
        $this->updateDB();
    }

    public function setTemperature($temperature)
    {
        if ($temperature == $this->targetTemp) {
            return;
        }
        $this->targetTemp = $temperature;
        $this->updateDB();
    }

    public function setColor($color)
    {
        if ($color == $this->color) {
            return;
        }
        $this->color = $color;
        $this->updateDB();
        $this->publishColor();
    }
    
    public function publishColor()
    {
        //turn off the other channel
        $this->turnOffOtherChannel();
        
        //publish the duty
        $this->publishDuty();
    }
    
    public function getDuty()
    {
        return $this->duty;
    }

    public function setState($state)
    {
        if ($state == $this->state) {
            return;
        }
        $this->state = $state;
        $this->publishState();
        $this->updateDB();
    }

    public function getState()
    {
        return $this->state;
    }

    public function getStateName()
    {
        return $this->state == 1 ? "on" : "off";
    }

    public function updateDB()     {
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
        $stmt->bind_param(
            'iiisiss',
            $this->state,
            $this->duty,
            $this->currentTemp,
            $this->targetTemp,
            $timestamp,
            $this->deviceId,
            $this->channelBlue
        );
        $stmt->execute();
    }

    public function getChannel($color = null)
    {
        if ($color == "blue") {
            return $this->channelBlue;
        } else if ($color == "green") {
            return $this->channelGreen;
        } else {
            return $this->getChannel($this->color);
        }
    }

    public function getOtherChannel($color = null)
    {
        if($color != null){
            if($color == "blue"){
                return $this->channelGreen;
            } else {
                return $this->channelBlue;
            }
        }

        if ($this->color == "blue") {
            return $this->channelGreen;
        } else {
            return $this->channelBlue;
        }
    }

    public function switchChannel($channel, $state) {}

    public function publishDuty()
    {

        $topic = $this->composeTopic();
        $msg = "{$this->getChannel()}:duty:{$this->duty}";
        if(!$this->noMqttSync){
            publish($topic, $msg);
        }
    }

    public function publishState()
    {
        if($this->duty > 0){
            return;
        }
        
        
        $topic = $this->composeTopic();
        $msg = "{$this->getChannel()}:state:{$this->getStateName()}";
        if(!$this->noMqttSync){
            publish($topic, $msg);
        }

        //now force turn off the other channel
        $this->turnOffOtherChannel();
    }

    public function turnOffOtherChannel()
    {
        $topic = $this->composeTopic();
        $otherChannel = $this->getOtherChannel();
        $msg = "{$otherChannel}:state:off";
        if(!$this->noMqttSync){
            publish($topic, $msg);
        }
    }

    public function composeTopic()
    {
        return "/user/roy/{$this->deviceId}";
    }
}