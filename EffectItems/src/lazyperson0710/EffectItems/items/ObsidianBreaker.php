<?php

namespace lazyperson0710\EffectItems\items;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;

class ObsidianBreaker {

    public static function execution(BlockBreakEvent $event): void {
        if ($event->getBlock()->getId() !== BlockLegacyIds::OBSIDIAN) {
            $event->cancel();
        }
    }
}
