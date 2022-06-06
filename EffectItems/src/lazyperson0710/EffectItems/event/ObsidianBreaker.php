<?php

namespace lazyperson0710\EffectItems\event;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class ObsidianBreaker implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('ObsidianBreaker') !== null) {//ObsidianBreaker
            if ($event->getBlock()->getId() !== BlockLegacyIds::OBSIDIAN) {
                $event->cancel();
            }
        }
    }
}
