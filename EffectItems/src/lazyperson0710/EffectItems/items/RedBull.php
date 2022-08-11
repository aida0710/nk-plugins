<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson0710\EffectItems\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class RedBull {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::JUMP_BOOST(), 20 * 60 * 3, 8, false);
        $vanillaEffect = VanillaEffects::JUMP_BOOST();
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
        $effect = new EffectInstance(VanillaEffects::SPEED(), 20 * 60 * 3, 25, false);
        $vanillaEffect = VanillaEffects::SPEED();
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
    }
}
