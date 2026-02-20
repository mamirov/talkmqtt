<?php

declare(strict_types=1);

namespace OCA\TalkMqtt\AppInfo;

use OCA\Talk\Events\BeforeCallEndedEvent;
use OCA\Talk\Events\BeforeUserJoinedRoomEvent;
use OCA\Talk\Events\BeforeAttendeesAddedEvent;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCA\Talk\Events\BeforeCallStartedEvent;
use OCA\Talk\Events\LobbyModifiedEvent;
use OCA\Talk\Events\RoomModifiedEvent;
use OCA\Talk\Events\SessionLeftRoomEvent;
use OCA\TalkMqtt\Listener\TalkMessageListener;
use TalkEvents;

class Application extends App implements IBootstrap
{
	public const APP_ID = 'talkmqtt';

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct()
	{
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void
	{
		include_once __DIR__ . '/../../vendor/autoload.php';

		foreach (TalkEvents::cases() as $event) {
			$context->registerEventListener($event->getEventClass(), TalkMessageListener::class);
		}
	}

	public function boot(IBootContext $context): void {}
}
