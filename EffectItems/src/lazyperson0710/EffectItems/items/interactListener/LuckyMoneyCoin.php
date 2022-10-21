<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class LuckyMoneyCoin {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $addMoney = match (mt_rand(1, 10)) {
            1 => 100,
            2 => 500,
            3 => 2500,
            4 => 3500,
            5 => 5000,
            6 => 8000,
            7 => 12000,
            8 => 25000,
            9 => 30000,
            10 => 35000,
        };
        EconomyAPI::getInstance()->addMoney($player, $addMoney);
        $player->sendMessage("§bLuckyMoneyCoin §7>> §a{$addMoney}円のお金が当たりました！");
        SoundPacket::Send($player, 'item.trident.return');
    }
}
