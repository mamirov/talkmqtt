<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Service;

use OCA\TalkMqtt\Db\MqttEventMapper;


class MqttService {

    private MqttEventMapper $mqttEventMapper;

    public function sendEvent($eventName,  $json_event, $brokerId): void {
        $this->mqttEventMapper->createEvent($eventName, $json_event, $brokerId);
    }
}
