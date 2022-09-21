<?php

namespace lazyperson0710\LoginBonus\calculation;

use lazyperson0710\LoginBonus\Main;
use pocketmine\player\Player;

class checkInventoryItem {

    public static function init(Player $player, int $requiredCount): bool {
        if ($requiredCount === 0) return true;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if (Main::LoginBonusItemInfo['id'] === $item->getId()) {
                if ($requiredCount <= $item->getCount()) {
                    $player->getInventory()->removeItem($item->setCount($requiredCount));
                    return true;
                }
            }
        }
        return false;
    }

}