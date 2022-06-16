<?php

namespace deceitya\miningtools\extensions\range;

use onebone\economyapi\EconomyAPI;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class CheckBuyItemCost {

    /**
     * @param Player $player
     * @param int $subtraction
     * @return bool
     */
    public function MoneyCheck(Player $player, int $subtraction): bool {
        if (EconomyAPI::getInstance()->myMoney($player) <= $subtraction) {
            $player->sendMessage('§bMiningTools §7>> §c所持金が足りません');
            return false;
        }
        return true;
    }

    /**
     * @param Player $player
     * @param int $count
     * @param int $checkBlock
     * @param string $checkItemNBT
     * @return bool
     */
    public function CostItemCheck(Player $player, int $count, int $checkBlock, string $checkItemNBT): bool {
        $ItemCount = 0;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($item->getId() === $checkBlock) {
                if ($item->getNamedTag()->getTag($checkItemNBT) !== null) {
                    $ItemCount += $item->getCount();
                }
            }
        }
        if ($ItemCount <= $count) {
            $player->sendMessage('§bMiningTools §7>> §c所持アイテム数量が足りません');
            return false;
        }
        return true;
    }

}