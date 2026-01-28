<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getTopicName()
 * @method void setTopicName(string $topicName)
 * @method int getBrokerId()
 * @method void setBrokerId(int $brokerId)
 */
class MqttOperation extends Entity implements \JsonSerializable
{
    /** @var string */
    protected $topicName;
    /** @var int */
    protected $brokerId;

    public function __construct() {
        $this->addType('topicName', 'string');
        $this->addType('brokerId', 'integer');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'topic_name' => $this->topicName,
            'broker_id' => (int) $this->brokerId,
        ];
    }
}