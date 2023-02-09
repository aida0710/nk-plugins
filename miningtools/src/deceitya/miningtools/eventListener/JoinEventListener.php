<?php

declare(strict_types = 1);
namespace deceitya\miningtools\eventListener;

use deceitya\miningtools\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEventListener implements Listener {

	public function onJoin(PlayerJoinEvent $event) : void {
		Main::$flag[$event->getPlayer()->getName()] = false;
	}
}
