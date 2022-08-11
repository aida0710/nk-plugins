<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson0710\EffectItems\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class Churu {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 60 * 3, 3, false);
        $vanillaEffect = VanillaEffects::SLOWNESS();
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
        $effect = new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 20 * 60 * 5, 1, false);
        $vanillaEffect = VanillaEffects::FIRE_RESISTANCE();
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
    }
}
