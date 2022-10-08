<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class HeavenGrass {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::REGENERATION(), 20 * 60 * 10, 255, false);
        AddEffectPacket::init($player, $effect, VanillaEffects::REGENERATION(), true);
        $effect = new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 60 * 10, 1, false);
        AddEffectPacket::init($player, $effect, VanillaEffects::MINING_FATIGUE(), true);
    }
}
