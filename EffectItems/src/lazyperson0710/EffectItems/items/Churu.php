<?php

namespace lazyperson0710\EffectItems\items;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\CookedMutton;

class Churu extends CookedMutton {

    public function getAdditionalEffects(): array {
        return [
            new EffectInstance(VanillaEffects::POISON(), 80, 10),
            new EffectInstance(VanillaEffects::SLOWNESS(), 80, 10),
            new EffectInstance(VanillaEffects::WEAKNESS(), 80, 10),
            new EffectInstance(VanillaEffects::MINING_FATIGUE(), 80, 10),
        ];
    }

    //public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
    //    $event->cancel();
    //    $player = $event->getPlayer();
    //    $item = $player->getInventory()->getItemInHand();
    //    $player->getInventory()->removeItem($item->setCount(1));
    //    $vanillaEffect = VanillaEffects::SLOWNESS();
    //    $effect = new EffectInstance($vanillaEffect, 20 * 60 * 3, 3, false);
    //    AddEffectPacket::init($player, $effect, $vanillaEffect, true);
    //    $vanillaEffect = VanillaEffects::FIRE_RESISTANCE();
    //    $effect = new EffectInstance($vanillaEffect, 20 * 60 * 5, 1, false);
    //    AddEffectPacket::init($player, $effect, $vanillaEffect, true);
    //}
}