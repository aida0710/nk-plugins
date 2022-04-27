<?php

namespace lazyperson0710\EffectItems\tools\shovel;

use pocketmine\block\BlockLegacyIds;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\Player;

class PoisonShovel implements Listener {

    public function onBreak(BlockBreakEvent $event){
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('PoisonShovel') !== null) {//PoisonShovel
            if (mt_rand(1, 150) === 150){
                $effect = new EffectInstance(VanillaEffects::POISON(), 60, 0, false);
                $vanillaEffect = VanillaEffects::POISON();
                $this->addEffect($player, $effect, $vanillaEffect);
                $effect = new EffectInstance(VanillaEffects::HUNGER(), 60, 0, false);
                $vanillaEffect = VanillaEffects::HUNGER();
                $this->addEffect($player, $effect, $vanillaEffect);
            }
        }
    }

    public function addEffect(Player $player, EffectInstance $effect, Effect $vanillaEffects) {
        $effectInstance = $player->getEffects()->get($vanillaEffects);
        if ($effectInstance === null) {
            $player->getEffects()->add($effect);
        } elseif ($effectInstance->getDuration() < 499) {
            $player->getEffects()->add($effect);
        }
    }
}
