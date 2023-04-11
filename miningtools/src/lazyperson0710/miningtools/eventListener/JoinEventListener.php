<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\eventListener;

use lazyperson0710\miningtools\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEventListener implements Listener {

	public function onJoin(PlayerJoinEvent $event) : void {
		Main::$flag[$event->getPlayer()->getName()] = false;
	}
}
