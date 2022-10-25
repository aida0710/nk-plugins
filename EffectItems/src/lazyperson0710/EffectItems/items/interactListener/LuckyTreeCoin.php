<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\SoundPacket;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;

class LuckyTreeCoin {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $addItem = match (mt_rand(1, 6)) {
            1 => VanillaBlocks::OAK_SAPLING()->asItem(),
            2 => VanillaBlocks::JUNGLE_SAPLING()->asItem(),
            3 => VanillaBlocks::SPRUCE_SAPLING()->asItem(),
            4 => VanillaBlocks::DARK_OAK_SAPLING()->asItem(),
            5 => VanillaBlocks::BIRCH_SAPLING()->asItem(),
            6 => VanillaBlocks::ACACIA_SAPLING()->asItem(),
        };
        if ($player->getInventory()->canAddItem($addItem)) {
            $player->getInventory()->addItem($addItem);
        } else {
            $player->dropItem($addItem);
        }
        $player->sendMessage("§bLuckyTreeSaplingCoin §7>> §a{$addItem->getName()}の苗が手に入りました！！");
        SoundPacket::Send($player, 'item.trident.return');
    }
}
