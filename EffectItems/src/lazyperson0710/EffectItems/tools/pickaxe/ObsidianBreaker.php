<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class ObsidianBreaker implements Listener {

    public function onInteract(PlayerInteractEvent $event): void {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        $block = $event->getBlock();
        $position = $player->getPosition();
        if ($inHand->getNamedTag()->getTag('ObsidianBreaker') !== null) {//ObsidianBreaker
            $pos = $block->getPosition()->add($position->getFloorX(), $position->getFloorY(), $position->getFloorZ());
            if ($event->getBlock()->getId() === BlockLegacyIds::OBSIDIAN) {
                //todo 土地保護などの条件追加
                $block->getPosition()->getWorld()->useBreakOn($pos, $inHand, $player);
            }
        }
    }

    public function onBreak(BlockBreakEvent $event){
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('ObsidianBreaker') !== null) {//ObsidianBreaker
            $event->cancel();
        }
    }
}
