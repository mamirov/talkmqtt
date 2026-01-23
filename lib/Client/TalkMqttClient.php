<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Client;

use PhpMqtt\Client\MqttClient;

class TalkMqttClient
{
    private $host = 'nanomq';
    private $mqtt;

    public function __construct()
    {
        $this->mqtt = new MqttClient($this->host);
        $this->mqtt->connect();
    }

    public function sendEvent(string $topic, string $message)
    {
        $this->mqtt->publish($topic, $message, 1);
    }
}
