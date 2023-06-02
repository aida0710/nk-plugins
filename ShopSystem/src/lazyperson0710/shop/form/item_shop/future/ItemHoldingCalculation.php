<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop\future;

use lazyperson0710\shop\object\ItemShopObject;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class ItemHoldingCalculation {

    use SingletonTrait;

    private int $storageCount;

    public function getHoldingCount(Player $player, ItemShopObject $item) : int {
        $count = 0;
        foreach ($player->getInventory()->getContents() as $inventoryItem) {
            if ($item->getItem() === $inventoryItem) {
                $count += $inventoryItem->getCount();
            }
        }
        return $count;
    }

}
