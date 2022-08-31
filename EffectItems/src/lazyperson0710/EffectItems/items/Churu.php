<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\AddEffectPacket;
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
        $vanillaEffect = VanillaEffects::SLOWNESS();
        $effect = new EffectInstance($vanillaEffect, 20 * 60 * 3, 3, false);
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
        $vanillaEffect = VanillaEffects::FIRE_RESISTANCE();
        $effect = new EffectInstance($vanillaEffect, 20 * 60 * 5, 1, false);
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
    }
}
