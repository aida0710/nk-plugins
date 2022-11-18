<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class LuckyExpCoin {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if (PlayerItemEvent::checkInterval($player) === false) return;

        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $addXp = match (mt_rand(1, 10)) {
            1 => 100,
            2 => 300,
            3 => 800,
            4 => 1400,
            5 => 2000,
            6 => 2500,
            7 => 2800,
            8 => 3200,
            9 => 4000,
            10 => 5000,
        };
        $player->getXpManager()->addXp($addXp);
        SendNoSoundMessage::Send($player, "{$addXp}xpが当たりました！", "LuckyExpCoin", true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
