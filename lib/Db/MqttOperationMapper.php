<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class MqttOperationMapper extends QBMapper
{
    public function __construct(IDBConnection $db) {
        parent::__construct($db, Tables::MQTT_OPERATIONS_TABLE_NAME, MqttOperation::class);
    }

    /**
     * @param int $id
     * @return MqttOperation
     * @throws DoesNotExistException
     * @throws MultipleObjectsReturnedException
     * @throws Exception
     */
    public function getOperation(int $id): MqttOperation {
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
     * @return MqttOperation[]
     * @throws Exception
     */
    public function getOperationsByBrokerId(int $brokerId): array {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('broker_id', $qb->createNamedParameter($brokerId, IQueryBuilder::PARAM_INT))
            );

        return $this->findEntities($qb);
    }

    /**
     * @return MqttOperation[]
     * @throws Exception
     */
    public function getAllOperations(): array {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from($this->getTableName());

        return $this->findEntities($qb);
    }

    /**
     * @param string $topicName
     * @param int $brokerId
     * @return MqttOperation
     * @throws Exception
     */
    public function createOperation(string $topicName, int $brokerId): MqttOperation {
        $operation = new MqttOperation();
        $operation->setTopicName($topicName);
        $operation->setBrokerId($brokerId);
        return $this->insert($operation);
    }

    /**
     * @param int $id
     * @param string|null $topicName
     * @return MqttOperation|null
     * @throws Exception
     */
    public function updateOperation(int $id, ?string $topicName = null): ?MqttOperation {
        if ($topicName === null) {
            return null;
        }
        try {
            $operation = $this->getOperation($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }
        $operation->setTopicName($topicName);
        return $this->update($operation);
    }

    /**
     * @param int $id
     * @return MqttOperation|null
     * @throws Exception
     */
    public function deleteOperation(int $id): ?MqttOperation {
        try {
            $operation = $this->getOperation($id);
        } catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
            return null;
        }

        return $this->delete($operation);
    }

    /**
     * @param int $brokerId
     * @return void
     * @throws Exception
     */
    public function deleteOperationsByBrokerId(int $brokerId): void {
        $qb = $this->db->getQueryBuilder();

        $qb->delete($this->getTableName())
            ->where(
                $qb->expr()->eq('broker_id', $qb->createNamedParameter($brokerId, IQueryBuilder::PARAM_INT))
            );
        $qb->executeStatement();
        $qb->resetQueryParts();
    }
}