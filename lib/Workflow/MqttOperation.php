<?php

namespace OCA\TalkMqtt\Workflow;

use OCA\Talk\Events\BeforeAttendeesAddedEvent;
use OCA\Talk\Events\BeforeCallEndedEvent;
use OCA\Talk\Events\BeforeCallStartedEvent;
use OCA\Talk\Events\BeforeUserJoinedRoomEvent;
use OCA\Talk\Events\LobbyModifiedEvent;
use OCA\Talk\Events\RoomModifiedEvent;
use OCA\Talk\Events\SessionLeftRoomEvent;
use OCA\TalkMqtt\Service\MqttService;
use OCP\EventDispatcher\Event;
use OCP\WorkflowEngine\IManager;
use OCP\WorkflowEngine\IRuleMatcher;
use OCP\WorkflowEngine\ISpecificOperation;
use ReflectionClass;

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
        if (!$event instanceof BeforeCallStartedEvent 
        && !$event instanceof BeforeCallEndedEvent 
        && !$event instanceof BeforeUserJoinedRoomEvent
        && !$event instanceof BeforeAttendeesAddedEvent
        && !$event instanceof LobbyModifiedEvent
        && !$event instanceof SessionLeftRoomEvent
        && !$event instanceof RoomModifiedEvent) {
            return;
        }
        $payload = [];
        $payload['event'] = $event::class;
        $payload['time'] = time();
        $payload = $this->serializeJson($event, $payload);
        $payload = json_encode($payload);
        $this->mqtt->sendEvent("nextcloud/talk/messages", $payload);
    }

    private function serializeJson($class, $payload)
    {
        if (is_array($class)) {
            foreach ($class as $arrayEl) {
                $payload[] = $this->serializeJson($arrayEl, $payload);
            }
        }
        $methods = get_class_methods($class);
        $reflectionClass = new ReflectionClass($class::class);
        foreach ($methods as $method) {
            if ($method == 'getFieldTypes') {
                foreach ($class->getFieldTypes() as $fieldType => $value) {
                    $payload[$fieldType] = $class->{'get' . ucfirst($fieldType)}();
                }
                continue;
            }
            if (str_starts_with($method, 'get') || str_starts_with($method, 'is')) {
                $fieldName = str_starts_with($method, 'get') ? lcfirst(substr($method, 3)) : $method;
                $refMethod = $reflectionClass->getMethod($method);
                if ($refMethod->getNumberOfParameters() > 0) {
                    continue;
                }
                $fieldValue = $class->$method();
                if (is_object($fieldValue) || (is_array($fieldValue) && !empty($fieldValue) && is_object($fieldValue[0]))) {
                    $payload[$fieldName] = [];
                    $payload[$fieldName] = $this->serializeJson($fieldValue, $payload[$fieldName]);
                } else {
                    $payload[$fieldName] = $fieldValue;
                }
            }
        }
        return $payload;
    }
}
