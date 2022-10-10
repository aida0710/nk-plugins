<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class Unknown {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 5, 5, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), 20 * 60 * 5, 1, false);
        AddEffectPacket::Add($player, $effect, VanillaEffects::NIGHT_VISION(), true);
    }
}
