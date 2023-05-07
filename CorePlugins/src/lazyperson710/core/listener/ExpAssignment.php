<?php

declare(strict_types = 1);

namespace lazyperson710\core\listener;

use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class ExpAssignment implements Listener {

	public function onBreak(BlockBreakEvent $event) : void {
		if ($event->isCancelled()) return;
		$player = $event->getPlayer();
		$exp = $event->getXpDropAmount();
		if ($event->getBlock()->getName() === VanillaBlocks::MONSTER_SPAWNER()->getName()) {
			$exp += 5000;
		}
		$player->getXpManager()->addXp($exp);
		$event->setXpDropAmount(0);
	}

}