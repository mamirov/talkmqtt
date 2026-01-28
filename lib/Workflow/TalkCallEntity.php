<?php

namespace OCA\TalkMqtt\Workflow;

use OCA\Talk\Events\BeforeAttendeesAddedEvent;
use OCA\Talk\Events\BeforeCallEndedEvent;
use OCA\Talk\Events\BeforeCallStartedEvent;
use OCA\Talk\Events\BeforeUserJoinedRoomEvent;
use OCA\Talk\Events\LobbyModifiedEvent;
use OCA\Talk\Events\RoomModifiedEvent;
use OCA\Talk\Events\SessionLeftRoomEvent;
use OCP\EventDispatcher\Event;
use OCP\WorkflowEngine\GenericEntityEvent;
use OCP\WorkflowEngine\IEntity;
use OCP\WorkflowEngine\IRuleMatcher;

class TalkCallEntity implements IEntity
{
    function getName(): string
    {
        return "Nextcloud Talk call events";
    }

    function getIcon(): string
    {
        return "";
    }

    function getEvents(): array
    {
        return [
            new GenericEntityEvent("Call started", "OCA\Talk\Events\BeforeCallStartedEvent"),
            new GenericEntityEvent("Call ended", "OCA\Talk\Events\BeforeCallEndedEvent"),
            new GenericEntityEvent("User joined room", "OCA\Talk\Events\BeforeUserJoinedRoomEvent"),
            new GenericEntityEvent("Attendees added", "OCA\Talk\Events\BeforeAttendeesAddedEvent"),
            new GenericEntityEvent("Lobby modified", "OCA\Talk\Events\LobbyModifiedEvent"),
            new GenericEntityEvent("Session left room", "OCA\Talk\Events\SessionLeftRoomEvent"),
            new GenericEntityEvent("Room modified", "OCA\Talk\Events\RoomModifiedEvent"),
        ];
    }

    function prepareRuleMatcher(IRuleMatcher $ruleMatcher, string $eventName, Event $event): void
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
    }

    function isLegitimatedForUserId(string $userId): bool
    {
        return true;
    }
}
