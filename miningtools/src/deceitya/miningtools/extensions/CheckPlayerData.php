<?php

namespace deceitya\miningtools\extensions;

use deceitya\miningtools\Main;
use lazyperson710\core\packet\SendMessage\SendMessage;
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
            SendMessage::Send($player, "所持金が足りないため処理を中断しました", "MiningTools", false);
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
        SendMessage::Send($player, "コストアイテムの所持数量が必要個数より少ない為処理を中断しました", "MiningTools", false);
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
            SendMessage::Send($player, "現在所持しているアイテムは旧バージョンのアイテムの為更新が必要です。更新はブロックを破壊することで可能です", "MiningTools", false);
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
            SendMessage::Send($player, "現在選択された機能はMiningTools専用機能の為所持しているアイテムでは使用できません", "MiningTools", false);
            return false;
        }
        if ($item->getId() === ItemIds::DIAMOND_PICKAXE || $player->getInventory()->getItemInHand()->getId() === ItemIds::DIAMOND_SHOVEL) {
            SendMessage::Send($player, "DiamondMiningToolsはアップグレードに対応していません", "MiningTools", false);
            return false;
        }
        if ($item->getId() === ItemIds::DIAMOND_AXE || $player->getInventory()->getItemInHand()->getId() === Main::NETHERITE_AXE) {
            SendMessage::Send($player, "MiningTools Axeはアップグレードに対応していません", "MiningTools", false);
            return false;
        }
        return true;
    }

}