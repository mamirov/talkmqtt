<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\AppInfo;

use OCA\TalkMqtt\Service\MqttService;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\EventDispatcher\IEventDispatcher;
use OCA\TalkMqtt\Workflow\MqttOperation;
use OCA\TalkMqtt\Workflow\TalkCallEntity;
use OCP\Util;
use OCP\WorkflowEngine\Events\RegisterEntitiesEvent;
use OCP\WorkflowEngine\Events\RegisterOperationsEvent;
use Psr\Container\ContainerInterface;

class Application extends App implements IBootstrap
{
	public const APP_ID = 'talkmqtt';

	public function __construct()
	{
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void
	{
		include_once __DIR__ . '/../../vendor/autoload.php';

		$dispatcher = $this->getContainer()->get(IEventDispatcher::class);
		if ($dispatcher instanceof IEventDispatcher) {
			$dispatcher->addListener(RegisterEntitiesEvent::class, function (RegisterEntitiesEvent $event) {
				$talkEntity = $this->getContainer()->get(TalkCallEntity::class);
				$event->registerEntity($talkEntity);
			});

			$dispatcher->addListener(RegisterOperationsEvent::class, function (RegisterOperationsEvent $event) {
				$mqttOperation = $this->getContainer()->get(MqttOperation::class);
				$event->registerOperation($mqttOperation);
				Util::addScript($this::APP_ID, $this::APP_ID . '-flow');
			});
		}
	}

	public function boot(IBootContext $context): void {}
}
