<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getEventName()
 * @method void setEventName(string $eventName)
 * @method string getEvent()
 * @method void setEvent(string $event)
 * @method int getBrokerId()
 * @method void setBrokerId(int $brokerId)
 * @method int getAttempts()
 * @method void setAttempts(int $attempts)
 * @method string|null getError()
 * @method void setError(?string $error)
 */
class MqttEvent extends Entity implements \JsonSerializable
{
    /** @var string */
    protected $eventName;
    /** @var string */
    protected $event;
    /** @var int */
    protected $brokerId;
    /** @var int */
    protected $attempts;
    /** @var string|null */
    protected $error;

    public function __construct() {
        $this->addType('eventName', 'string');
        $this->addType('event', 'string');
        $this->addType('brokerId', 'integer');
        $this->addType('attempts', 'integer');
        $this->addType('error', 'string');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'event_name' => $this->eventName,
            'event' => $this->event,
            'broker_id' => (int) $this->brokerId,
            'attempts' => (int) $this->attempts,
            'error' => $this->error,
        ];
    }
}