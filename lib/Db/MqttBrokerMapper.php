<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class MqttBrokerMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, Tables::MQTT_BROKERS_TABLE_NAME, MqttBroker::class);
    }

    /**
     * @param int $id
     * @return MqttBroker
     * @throws DoesNotExistException
     * @throws MultipleObjectsReturnedException
     * @throws Exception
     */
    public function getBroker(int $id): MqttBroker {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
            );

        return $this->findEntity($qb);
    }

    /**
     * @return MqttBroker[]
     * @throws Exception
     */
    public function getAllBrokers(): array {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName());

        return $this->findEntities($qb);
    }

    /**
     * @param string $url
     * @param string $name
     * @return MqttBroker
     * @throws Exception
     */
    public function createBroker(string $url, string $name): MqttBroker {
        $broker = new MqttBroker();
        $broker->setUrl($url);
        $broker->setName($name);
        return $this->insert($broker);
    }

    /**
     * @param int $id
     * @param string|null $url
     * @param string|null $name
     * @return MqttBroker|null
     * @throws Exception
     */
    public function updateBroker(int $id, ?string $url = null, ?string $name = null): ?MqttBroker {
        if ($url === null && $name === null) {
            return null;
        }
        try {
            $broker = $this->getBroker($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }
        if ($url !== null) {
            $broker->setUrl($url);
        }
        if ($name !== null) {
            $broker->setName($name);
        }
        return $this->update($broker);
    }

    /**
     * @param int $id
     * @return MqttBroker|null
     * @throws Exception
     */
    public function deleteBroker(int $id): ?MqttBroker {
        try {
            $broker = $this->getBroker($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }

        return $this->delete($broker);
    }
}