<?php

namespace OCA\TalkMqtt\Migration;

use OCP\Migration\IOutput;
use OCP\DB\ISchemaWrapper;
use Closure;
use Doctrine\DBAL\Schema\Table;
use OCA\TalkMqtt\Db\Tables;
use OCP\DB\Types;
use OCP\Migration\SimpleMigrationStep;

declare(strict_types=1);

class Version010101Date20260123081800 extends SimpleMigrationStep
{

    function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
    {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable(Tables::MQTT_EVENTS_TABLE_NAME)) {
            $table = $schema->createTable(Tables::MQTT_EVENTS_TABLE_NAME);
            $table->addColumn('id', Types::BIGINT, [
                'autoincrement' => true,
                'notnull' => true,
                'length' => 4,
            ]);
            $table->addColumn('event_name', Types::STRING, [
                'notnull' => true,
                'length' => 255,
            ]);
            $table->addColumn('event', Types::JSON, [
                'notnull' => true,
            ]);
            $table->addColumn('broker_id', Types::BIGINT, [
                'notnull' => true,
                'length' => 4,
            ]);
            $table->addColumn('attempts', Types::INTEGER, [
                'notnull' => true,
                'default' => 5,
            ]);
            $table->addColumn('error', Types::STRING);
            $table->setPrimaryKey(['id']);
            $table->addForeignKeyConstraint(Tables::MQTT_BROKERS_TABLE_NAME, ['broker_id'], ['id']);
        }

        return $schema;
    }
}
