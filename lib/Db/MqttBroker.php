<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getUrl()
 * @method void setUrl(string $url)
 * @method string getName()
 * @method void setName(string $name)
 */
class MqttBroker extends Entity implements \JsonSerializable
{
    /** @var string */
    protected $url;
    /** @var string */
    protected $name;

    public function __construct() {
        $this->addType('url', 'string');
        $this->addType('name', 'string');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'name' => $this->name,
        ];
    }
}