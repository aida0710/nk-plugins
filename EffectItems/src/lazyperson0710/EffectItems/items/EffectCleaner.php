<?php

namespace lazyperson0710\EffectItems\items;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class EffectCleaner {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $player->getInventory()->removeItem($item->setCount(1));
        $player->getEffects()->clear();
    }
}
