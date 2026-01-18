<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Listener;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCA\Talk\Events\BeforeCallStartedEvent;
use OCA\TalkMqtt\Service\MqttService;

use function OCP\Log\logger;

class TalkMessageListener implements IEventListener
{

    private $mqttService;

    public function __construct(MqttService $mqttService) {
        $this->mqttService = $mqttService;
    }

    public function handle(Event $event): void
    {
        if (!($event instanceof BeforeCallStartedEvent)) {
            return;
        }

        $room = $event->getRoom();
        $payload = json_encode([
            'time'    => time(),
            'event' => 'BeforeCallStartedEvent',
            'actor' => $event->getActor()->getAttendee()->getDisplayName(),
            'room' => [
                'name' => $room->getName(),
                'token' => $room->getToken(),
            ],
            'link' => $event->getRoom()->getRemoteServer()
        ]);

        $this->mqttService->sendEvent('nextcloud/talk/messages', $payload);
        // logger('talkmqtt') -> error('Publishing Talk message to MQTT: ' . print_r($event, true));
        // Send to MQTT topic
        // $this->mqttService->publish('nextcloud/talk/messages', $payload);
    }
}
