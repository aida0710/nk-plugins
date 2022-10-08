<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\AddEffectPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\CookedMutton;

class Churu extends CookedMutton {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 60 * 3, 3, false);
        AddEffectPacket::init($player, $effect, VanillaEffects::SLOWNESS(), true);
        $effect = new EffectInstance(VanillaEffects::FIRE_RESISTANCE(), 20 * 60 * 5, 1, false);
        AddEffectPacket::init($player, $effect, VanillaEffects::FIRE_RESISTANCE(), true);
    }
}