<?php

namespace OCA\TalkMqtt\Workflow;

use OCA\Talk\Events\BeforeCallStartedEvent;
use OCP\EventDispatcher\Event;
use OCP\WorkflowEngine\GenericEntityEvent;
use OCP\WorkflowEngine\IEntity;
use OCP\WorkflowEngine\IRuleMatcher;

class TalkCallEntity implements IEntity {
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
            new GenericEntityEvent("Conv started", "OCA\Talk\Events\BeforeCallStartedEvent"),
        ];
    }

    function prepareRuleMatcher(IRuleMatcher $ruleMatcher, string $eventName, Event $event): void
    {
        if (!$event instanceof BeforeCallStartedEvent) {
            return;
        }
    }

    function isLegitimatedForUserId(string $userId): bool
    {
        return true;
    }
}