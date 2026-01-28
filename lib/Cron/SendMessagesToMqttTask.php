<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\Cron;

use OCA\TalkMqtt\Service\MqttService;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\QueuedJob;
use OCP\BackgroundJob\TimedJob;

use function OCP\Log\logger;

/**
 * class for fetching events from db and send them to mqtt broker
 */
class SendMessagesToMqttTask extends TimedJob
{

    public function __construct(ITimeFactory $time)
    {
         parent::__construct($time);
         $this->setInterval(60 * 5); // every 5 minutes depends on the cron configuration
         $this->setAllowParallelRuns(false);
    }

    protected function run($arguments) {
        //TODO: implement fetching events from db and sending them to mqtt broker
        logger('talkmqtt')->debug('SendMessagesToMqttTask executed');
    }
}
