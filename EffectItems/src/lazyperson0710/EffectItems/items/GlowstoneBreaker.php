<?php

namespace lazyperson0710\EffectItems\items;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;

class GlowstoneBreaker {

    public static function init(BlockBreakEvent $event): void {
        if ($event->getBlock()->getId() !== BlockLegacyIds::GLOWSTONE) {
            $event->cancel();
        }
    }
}
