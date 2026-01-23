<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class MqttEventMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, Tables::MQTT_EVENTS_TABLE_NAME, MqttEvent::class);
    }

    /**
     * @param int $id
     * @return MqttEvent
     * @throws DoesNotExistException
     * @throws MultipleObjectsReturnedException
     * @throws Exception
     */
    public function getEvent(int $id): MqttEvent {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
            );

        return $this->findEntity($qb);
    }

    /**
     * @param int $brokerId
     * @return MqttEvent[]
     * @throws Exception
     */
    public function getEventsByBrokerId(int $brokerId): array {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('broker_id', $qb->createNamedParameter($brokerId, IQueryBuilder::PARAM_INT))
            );

        return $this->findEntities($qb);
    }

    /**
     * @return MqttEvent[]
     * @throws Exception
     */
    public function getAllEvents(): array {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName());

        return $this->findEntities($qb);
    }

    /**
     * @param string $eventName
     * @param string $event
     * @param int $brokerId
     * @param int $attempts
     * @param string|null $error
     * @return MqttEvent
     * @throws Exception
     */
    public function createEvent(string $eventName, string $event, int $brokerId, int $attempts = 5, ?string $error = null): MqttEvent {
        $mqttEvent = new MqttEvent();
        $mqttEvent->setEventName($eventName);
        $mqttEvent->setEvent($event);
        $mqttEvent->setBrokerId($brokerId);
        $mqttEvent->setAttempts($attempts);
        $mqttEvent->setError($error);
        return $this->insert($mqttEvent);
    }

    /**
     * @param int $id
     * @param string|null $eventName
     * @param string|null $event
     * @param int|null $attempts
     * @param string|null $error
     * @return MqttEvent|null
     * @throws Exception
     */
    public function updateEvent(int $id, ?string $eventName = null, ?string $event = null, ?int $attempts = null, ?string $error = null): ?MqttEvent {
        if ($eventName === null && $event === null && $attempts === null && $error === null) {
            return null;
        }
        try {
            $mqttEvent = $this->getEvent($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }
        if ($eventName !== null) {
            $mqttEvent->setEventName($eventName);
        }
        if ($event !== null) {
            $mqttEvent->setEvent($event);
        }
        if ($attempts !== null) {
            $mqttEvent->setAttempts($attempts);
        }
        if ($error !== null) {
            $mqttEvent->setError($error);
        }
        return $this->update($mqttEvent);
    }

    /**
     * @param int $id
     * @return MqttEvent|null
     * @throws Exception
     */
    public function deleteEvent(int $id): ?MqttEvent {
        try {
            $mqttEvent = $this->getEvent($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }

        return $this->delete($mqttEvent);
    }

    /**
     * @param int $brokerId
     * @return void
     * @throws Exception
     */
    public function deleteEventsByBrokerId(int $brokerId): void {
        $qb = $this->db->getQueryBuilder();

        $qb->delete($this->getTableName())
            ->where(
                $qb->expr()->eq('broker_id', $qb->createNamedParameter($brokerId, IQueryBuilder::PARAM_INT))
            );
        $qb->executeStatement();
        $qb->resetQueryParts();
    }
}