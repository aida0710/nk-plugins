<?php

declare(strict_types=1);

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use function in_array;

class StopHunger implements Listener {

	public function onHunger(PlayerExhaustEvent $event) {
		$WorldName = $event->getPlayer()->getWorld()->getFolderName();
		if (in_array($WorldName, WorldCategory::PublicWorld, true) || in_array($WorldName, WorldCategory::PublicEventWorld, true)) {
			$event->cancel();
		}
	}
}
