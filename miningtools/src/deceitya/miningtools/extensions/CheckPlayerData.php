<?php

namespace deceitya\miningtools\extensions;

use deceitya\miningtools\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class CheckPlayerData {

    /**
     * @param Player $player
     * @param int $subtraction
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
     * @param int $count
     * @param int $checkBlock
     * @param string $tagName
     * @return bool
     */
    public function CheckReduceCostItem(Player $player, int $count, int $checkBlock, string $tagName): bool {
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
        return true;
    }

    public function ReduceCostItem(Player $player, int $count, int $checkBlock, string $tagName): bool {
        $nbt = CompoundTag::create()->setString($tagName, "1");
        $costItem = ItemFactory::getInstance()->get($checkBlock, 0, $count, $nbt);
        $player->getInventory()->removeItem($costItem);
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