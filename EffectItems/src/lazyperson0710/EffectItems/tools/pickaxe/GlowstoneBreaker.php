<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class GlowstoneBreaker implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('GlowstoneBreaker') !== null) {//GlowstoneBreaker
            if ($event->getBlock()->getId() !== BlockLegacyIds::GLOWSTONE) {
                $event->cancel();
            }
        }
    }
}
