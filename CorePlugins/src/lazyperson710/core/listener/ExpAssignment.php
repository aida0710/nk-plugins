<?php

declare(strict_types = 1);

namespace lazyperson710\core\listener;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class ExpAssignment implements Listener {

	public function onBreak(BlockBreakEvent $event) : void {
		if ($event->isCancelled()) return;
		if ($event->getBlock()->getName() === VanillaBlocks::MONSTER_SPAWNER()->getName()) {
			$exp = $event->getXpDropAmount();
			$exp += 5000;
			$player = $event->getPlayer();
			$player->getXpManager()->addXp($exp);
			$event->setXpDropAmount(0);
		}
	}

}