<?php

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\GameMode;

class LuckyFoodCoin {

    public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item): void {
        $event->cancel();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            $player->getInventory()->removeItem($item->setCount(1));
        }
        $addItem = match (mt_rand(1, 6)) {
            1 => VanillaItems::COOKED_SALMON(),//的な感じで
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
        //ここも変える必要があります
        SendNoSoundMessage::Send($player, "{$addItem->getName()}の苗が手に入りました！！", "LuckyTreeSaplingCoin", true);
        SoundPacket::Send($player, 'item.trident.return');
    }
}
