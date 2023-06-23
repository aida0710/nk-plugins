<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\listener\ExpAssignment;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class CompressionLevelItem {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if (PlayerItemEvent::checkInterval($player) === false) return;
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $exp = ExpAssignment::MONSTER_SPAWNER_EXP * 10;
        $player = $event->getPlayer();
        $player->getXpManager()->addXp($exp);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
