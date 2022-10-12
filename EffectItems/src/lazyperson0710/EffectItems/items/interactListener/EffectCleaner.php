<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\player\GameMode;

class EffectCleaner {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $player->getEffects()->clear();
        SoundPacket::Send($player, 'item.trident.return');
    }
}
