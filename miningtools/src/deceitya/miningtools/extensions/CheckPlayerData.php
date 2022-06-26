<?php

namespace deceitya\miningtools\extensions;

use deceitya\miningtools\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class CheckPlayerData {

    /**
     * @param Player $player
     * @param int $subtraction
     * @return bool
     */
    public function ReduceMoney(Player $player, int $subtraction): bool {
        if (EconomyAPI::getInstance()->myMoney($player) <= $subtraction) {
            $player->sendMessage('§bMiningTools §7>> §c所持金が足りません');
            return false;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $subtraction);
        return true;
    }

    /**
     * @param Player $player
     * @param int $count
     * @param int $checkBlock
     * @param string $tagName
     * @return bool
     */
    public function ReduceCostItem(Player $player, int $count, int $checkBlock, string $tagName): bool {
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
            $player->sendMessage(Main::PrefixRed . 'コストアイテムの所持数量が必要個数より少ない為処理を中断しました');
            return false;
        }
        $costItem = ItemFactory::getInstance()->get($checkBlock);
        $player->getInventory()->removeItem($costItem->setCount($count));
        $inventory = $player->getInventory();
        $inventory->removeItem($costItem);
        return true;
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
            $player->sendMessage("§bMiningTools §7>> §cDiamondMiningToolsはアップグレードに対応していません");
            return false;
        }
        if ($item->getId() === ItemIds::DIAMOND_AXE || $player->getInventory()->getItemInHand()->getId() === Main::NETHERITE_AXE) {
            $player->sendMessage("§bMiningTools §7>> §cMiningTools Axeはアップグレードに対応していません");
            return false;
        }
        return true;
    }

}