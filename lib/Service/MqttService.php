<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Service;

use PhpMqtt\Client\MqttClient;

use function OCP\Log\logger;

class MqttService 
{
    private $host = 'nanomq';
    private $mqtt;

    public function __construct() {
        $this->mqtt = new MqttClient($this->host);
    }

    public function sendEvent(string $topic, string $message) {
        $this->mqtt->connect();
        try {
            $this->mqtt->publish($topic, $message, 1); 
        } catch (\Exception $e) {
            logger('talkmqtt')->error('MQTT publish error: ' . $e->getMessage());
        } finally {
            if ($this->mqtt->isConnected()) {
                $this->mqtt->disconnect();
            }
        }
    }
}