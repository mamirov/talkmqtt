<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Migration;

use OCP\Migration\IOutput;
use Closure;
use OCA\TalkMqtt\Db\Tables;
use OCP\Migration\SimpleMigrationStep;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;

class Version010100Date20260122214700 extends SimpleMigrationStep {

    

    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
    {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure;

        $schema = $this->createMqttBrokerTable($schema);
        $schema = $this->createMqttOperationTable($schema);
    }

    private function createMqttBrokerTable(ISchemaWrapper $schema) {
        if ($schema->hasTable(Tables::MQTT_BROKERS_TABLE_NAME)) {
            return;
        }
        $table = $schema->createTable(Tables::MQTT_BROKERS_TABLE_NAME);
        $table->addColumn('id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
        $table->addColumn('url', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
         $table->addColumn('name', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
        $table->setPrimaryKey(['id']);

        return $schema;
    }

    private function createMqttOperationTable(ISchemaWrapper $schema) {
        if ($schema->hasTable(Tables::MQTT_OPERATIONS_TABLE_NAME)) {
            return;
        }
        $table = $schema->createTable(Tables::MQTT_OPERATIONS_TABLE_NAME);
        $table->addColumn('id', Types::BIGINT, [
				'autoincrement' => true,
				'notnull' => true,
				'length' => 4,
			]);
        $table->addColumn('topic_name', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
         $table->addColumn('broker_id', Types::BIGINT, [
				'notnull' => true,
				'length' => 4,
			]);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint(Tables::MQTT_BROKERS_TABLE_NAME, ['broker_id'], ['id']);

        return $schema;
    }
}