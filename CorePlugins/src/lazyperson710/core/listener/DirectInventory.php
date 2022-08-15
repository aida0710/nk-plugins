<?php

namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\DirectDropItemStorageSetting;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class DirectInventory implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $drops = $event->getDrops();
        $player = $event->getPlayer();
        $event->setDrops([]);
        if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(DirectDropItemStorageSetting::getName())?->getValue() === true) {
            foreach ($drops as $item) {
                if ($this->notStorageItem($player, $item) === false) continue;
                StackStorageAPI::$instance->add($player->getXuid(), $item);
                $player->sendActionBarMessage("§bStorage §7>> §a" . $item->getName() . "が倉庫にしまわれました");
            }
        } else {
            foreach ($drops as $item) {
                if ($this->notStorageItem($player, $item) === false) continue;
                if ($player->getInventory()->canAddItem($item)) {
                    $player->getInventory()->addItem($item);
                } else {
                    StackStorageAPI::$instance->add($player->getXuid(), $item);
                    $player->sendActionBarMessage("§bStorage §7>> §aインベントリに空きが無いため" . $item->getName() . "が倉庫にしまわれました");
                }
            }
        }
    }

    public function notStorageItem(Player $player, Item $item): bool {
        if ($item->getVanillaName() == VanillaItems::AIR()->getVanillaName()) {
            var_dump("airの為キャンセル");
            return false;
        }
        if ($item->getVanillaName() == VanillaBlocks::DYED_SHULKER_BOX()->getName()) {
            var_dump("DYED_SHULKER_BOXの為キャンセル");
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else {
                $player->dropItem($item);
                return false;
            }
        }
        if ($item->getVanillaName() == VanillaBlocks::SHULKER_BOX()->getName()) {
            var_dump("SHULKER_BOXの為キャンセル");
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else {
                $player->dropItem($item);
                return false;
            }
        }
        return true;
    }
}