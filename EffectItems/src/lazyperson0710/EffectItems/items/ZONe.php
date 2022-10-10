<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class ZONe {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 60 * 5, 3, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::RESISTANCE(), true);
        $effect = new EffectInstance(VanillaEffects::STRENGTH(), 20 * 60 * 5, 8, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::STRENGTH(), true);
    }
}
