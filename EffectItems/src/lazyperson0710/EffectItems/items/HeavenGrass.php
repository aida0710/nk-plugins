<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson0710\EffectItems\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class HeavenGrass {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 60 * 10, 255, false);
        $vanillaEffect = VanillaEffects::REGENERATION();
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
        $effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 60 * 10, 1, false);
        $vanillaEffect = VanillaEffects::MINING_FATIGUE();
        AddEffectPacket::init($player, $effect, $vanillaEffect, true);
    }
}
