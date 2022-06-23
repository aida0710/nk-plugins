<?php

namespace deceitya\miningtools\extensions;

use onebone\economyapi\EconomyAPI;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class CheckPlayerData {

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
     * @param string $tagName
     * @return bool
     */
    public function CostItemCheck(Player $player, int $count, int $checkBlock, string $tagName): bool {
        $ItemCount = 0;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($item->getId() === $checkBlock) {
                if ($item->getNamedTag()->getTag($tagName) !== null) {
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

    /**
     * @param Player $player
     * @return bool
     */
    public function checkMining4(Player $player): bool {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('4mining') !== null) {
            $player->sendMessage("現在所持しているアイテムは旧バージョンのアイテムの為更新が必要です。更新はブロックを破壊することで可能です");
            return false;
        }
        return true;
    }

    public function checkNBTInt(Player $player, string $tagName, int $value): bool {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag($tagName) === null) {
            $player->sendMessage("現在所持しているアイテムは拡張機能選択時と持っているアイテムと内部データが違う為処理が中断されました");
            return false;
        }
        $now = $player->getInventory()->getItemInHand()->getNamedTag()->getInt($tagName);
        if ($now !== $value) {
            $player->sendMessage("現在所持しているアイテムは拡張機能選択時と持っているアイテムと内部データが違う為処理が中断されました");
            return false;
        }
        return true;
    }

}