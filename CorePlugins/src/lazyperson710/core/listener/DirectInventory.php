<?php

namespace lazyperson710\core\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use ree_jp\stackstorage\api\StackStorageAPI;

class DirectInventory implements Listener {

    /**
     * @param BlockBreakEvent $ev
     * @priority HIGH
     * @ignoreCancelled
     */
    public function onBreak(BlockBreakEvent $ev) {
        $drops = $ev->getDrops();
        $p = $ev->getPlayer();
        $nbt = $ev->getItem()->getNamedTag();
        //if (!$nbt->getTag("mining")) {
        $ev->setDrops([]);
        foreach ($drops as $item) {
            if ($p->getInventory()->canAddItem($item)) {
                $p->getInventory()->addItem($item);
            } else {
                StackStorageAPI::$instance->add($p->getXuid(), $item);
                $p->sendActionBarMessage("§bStorage §7>> §aインベントリに空きが無いため" . $item . "が倉庫にしまわれました");
            }
        }
        //}
    }
}