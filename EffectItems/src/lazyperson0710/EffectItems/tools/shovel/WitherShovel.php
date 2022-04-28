<?php

namespace lazyperson0710\EffectItems\tools\shovel;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class WitherShovel implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('WitherShovel') !== null) {//WitherShovel
            if (mt_rand(1, 300) === 300) {
                $effect = new EffectInstance(VanillaEffects::WITHER(), 60, 0, false);
                $effectInstance = $player->getEffects()->get(VanillaEffects::WITHER());
                if ($effectInstance === null) {
                    $player->getEffects()->add($effect);
                } elseif ($effectInstance->getDuration() < 499) {
                    $player->getEffects()->add($effect);
                }
            }
        }
    }
}
