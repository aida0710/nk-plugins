<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class GlowstoneBreaker implements Listener {

    public function onInteract(PlayerInteractEvent $event): void {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        $block = $event->getBlock();
        $position = $player->getPosition();
        if ($inHand->getNamedTag()->getTag('GlowstoneBreaker') !== null) {//GlowstoneBreaker
            $pos = $block->getPosition()->add($position->getFloorX(), $position->getFloorY(), $position->getFloorZ());
            if ($event->getBlock()->getId() === BlockLegacyIds::GLOWSTONE) {
                $block->getPosition()->getWorld()->useBreakOn($pos, $inHand, $player);
            }
        }
    }

    public function onBreak(BlockBreakEvent $event){
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('GlowstoneBreaker') !== null) {//GlowstoneBreaker
            $event->cancel();
        }
    }
}
