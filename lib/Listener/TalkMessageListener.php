<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Listener;

use OCA\TalkMqtt\Service\MqttService;
use OCA\TalkMqtt\Util\JsonUtil;
use OCP\EventDispatcher\Event;

use OCP\EventDispatcher\IEventListener;
use TalkEvents;

class TalkMessageListener implements IEventListener
{

    private $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    public function handle(Event $event): void
    {
        if (!in_array($event::class, TalkEvents::cases(), true)) {
            return;
        }
        $eventType = TalkEvents::from($event::class);

        $payload = JsonUtil::serializeJson($event::class, $event);
        $payload['eventType'] = $eventType->eventName();

        $this->mqttService->sendEvent('nextcloud/talk/messages', json_encode($payload));
    }
}
