<?php

declare(strict_types=1);

use OCP\Util;

Util::addScript(OCA\TalkMqtt\AppInfo\Application::APP_ID, OCA\TalkMqtt\AppInfo\Application::APP_ID . '-main');
Util::addStyle(OCA\TalkMqtt\AppInfo\Application::APP_ID, OCA\TalkMqtt\AppInfo\Application::APP_ID . '-main');

?>

<div id="talkmqtt"></div>
