<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Service;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttService 
{
    private $host = 'nanomq';
    private $port = 1883;
    private $mqtt;

    public function __construct() {
        $this->mqtt = new MqttClient($this->host);
        $this->mqtt->connect();
    }

    public function sendEvent(string $topic, string $message) {
        $this->mqtt->publish($topic, $message, 1); 
    }
}