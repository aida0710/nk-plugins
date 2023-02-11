<?php

declare(strict_types = 0);
namespace lazyperson0710\EffectItems\items\breakListener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;

class GlowstoneBreaker {

	public static function execution(BlockBreakEvent $event) : void {
		if ($event->getBlock()->getId() !== BlockLegacyIds::GLOWSTONE) {
			$event->cancel();
		}
	}
}
