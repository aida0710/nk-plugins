<?php

namespace lazyperson710\core\listener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
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
        foreach ($drops as $item) {
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else {
                if ($item->getId() === BlockLegacyIds::AIR) {
                    continue;
                }
                StackStorageAPI::$instance->add($player->getXuid(), $item);
                $player->sendActionBarMessage("§bStorage §7>> §aインベントリに空きが無いため" . $item->getName() . "が倉庫にしまわれました");
            }
        }
    }
}