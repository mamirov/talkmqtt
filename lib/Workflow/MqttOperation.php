<?php

namespace OCA\TalkMqtt\Workflow;

use OCA\Talk\Events\BeforeCallStartedEvent;
use OCA\TalkMqtt\Service\MqttService;
use OCP\EventDispatcher\Event;
use OCP\WorkflowEngine\IManager;
use OCP\WorkflowEngine\IRuleMatcher;
use OCP\WorkflowEngine\ISpecificOperation;

class MqttOperation implements ISpecificOperation
{

    private $mqtt;

    function __construct(MqttService $mqtt)
    {
        $this->mqtt = $mqtt;
    }

    function getDisplayName(): string
    {
        return "MQTT Op";
    }

    function getDescription(): string
    {
        return "Send an event to a MQTT topic";
    }

    function getEntityId(): string
    {
        return TalkCallEntity::class;
    }

    function getIcon(): string
    {
        return "";
    }

    function isAvailableForScope(int $scope): bool
    {
        return $scope == IManager::SCOPE_ADMIN;
    }

    function validateOperation(string $name, array $checks, string $operation): void {}

    function onEvent(string $eventName, Event $event, IRuleMatcher $ruleMatcher): void
    {
        if (!$event instanceof BeforeCallStartedEvent) {
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
        $this->mqtt->sendEvent("nextcloud/talk/messages", $payload);
    }
}
