<?php

namespace deceitya\miningtools\extensions;

use deceitya\miningtools\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class CheckPlayerData {

    /**
     * @param Player $player
     * @param int    $subtraction
     * @return bool
     */
    public function CheckReduceMoney(Player $player, int $subtraction): bool {
        if (EconomyAPI::getInstance()->myMoney($player) <= $subtraction) {
            $player->sendMessage(Main::PrefixRed . '所持金が足りないため処理を中断しました');
            return false;
        }
        return true;
    }

    /**
     * @param Player $player
     * @param int    $count
     * @param int    $checkItem
     * @param string $tagName
     * @return bool
     */
    public function CheckAndReduceCostItem(Player $player, int $count, int $checkItem, string $tagName): bool {
        if ($count === 0) return true;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($item->getId() !== $checkItem) continue;
            if ($item->getNamedTag()->getTag($tagName) !== null) {
                if ($count <= $item->getCount()) {
                    $player->getInventory()->removeItem($item->setCount($count));
                    return true;
                }
            }
        }
        $player->sendMessage(Main::PrefixRed . 'コストアイテムの所持数量が必要個数より少ない為処理を中断しました');
        return false;
    }

    /**
     * @param Player $player
     * @param int    $count
     * @param int    $checkItem
     * @param string $tagName
     * @return bool
     */
    public function CheckCostItem(Player $player, int $count, int $checkItem, string $tagName): bool {
        if ($count === 0) return true;
        for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
            $item = clone $player->getInventory()->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if ($item->getId() !== $checkItem) continue;
            if ($item->getNamedTag()->getTag($tagName) !== null) {
                if ($count <= $item->getCount()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function checkMining4(Player $player): bool {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('4mining') !== null) {
            $player->sendMessage(Main::PrefixRed . "現在所持しているアイテムは旧バージョンのアイテムの為更新が必要です。更新はブロックを破壊することで可能です");
            return false;
        }
        return true;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function checkMiningToolsNBT(Player $player): bool {
        $item = $player->getInventory()->getItemInHand();
        if (!($item->getNamedTag()->getTag('MiningTools_3') !== null || $item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null)) {
            $player->sendMessage(Main::PrefixRed . "現在選択された機能はMiningTools専用機能の為所持しているアイテムでは使用できません");
            return false;
        }
        if ($item->getId() === ItemIds::DIAMOND_PICKAXE || $player->getInventory()->getItemInHand()->getId() === ItemIds::DIAMOND_SHOVEL) {
            $player->sendMessage(Main::PrefixRed . "DiamondMiningToolsはアップグレードに対応していません");
            return false;
        }
        if ($item->getId() === ItemIds::DIAMOND_AXE || $player->getInventory()->getItemInHand()->getId() === Main::NETHERITE_AXE) {
            $player->sendMessage(Main::PrefixRed . "MiningTools Axeはアップグレードに対応していません");
            return false;
        }
        return true;
    }

}