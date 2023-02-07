<?php

declare(strict_types=1);

namespace lazyperson0710\EffectItems\items\breakListener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;

class ObsidianBreaker {

	public static function execution(BlockBreakEvent $event) : void {
		if ($event->getBlock()->getId() !== BlockLegacyIds::OBSIDIAN) {
			$event->cancel();
		}
	}
}
