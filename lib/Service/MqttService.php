<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Service;

use PhpMqtt\Client\MqttClient;

class MqttService 
{
    private $host = 'nanomq';
    private $mqtt;

    public function __construct() {
        $this->mqtt = new MqttClient($this->host);
        $this->mqtt->connect();
    }

    public function sendEvent(string $topic, string $message) {
        if (!$this->mqtt->isConnected()) {
            $this->mqtt->connect();
        }
        $this->mqtt->publish($topic, $message, 1); 
    }
}