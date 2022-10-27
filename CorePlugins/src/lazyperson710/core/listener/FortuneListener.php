<?php

namespace lazyperson710\core\listener;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class FortuneListener implements Listener {

    /**
     * @priority LOW
     */
    public function onBreak(BlockBreakEvent $event) {
        $enchant = $event->getItem()->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE));
        if ($enchant === null) {
            return;
        }
        if ($event->getItem()->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::SILK_TOUCH)) !== null) {
            SendMessage::Send($event->getPlayer(), "シルクタッチと幸運が同時付与されているツールは使用することができません", "Enchant", false);
            return;
        }
        if (empty($event->getDrops())) return;
        $plus = $this->Calculation($event->getBlock(), $enchant->getLevel());
        $item = $event->getDrops()[0];
        $item->setCount($item->getCount() + $plus);
    }

    static function Calculation(Block $block, int $level): int {
        if (!in_array($block->getId(), [
            BlockLegacyIds::DIAMOND_ORE,
            BlockLegacyIds::LAPIS_ORE,
            BlockLegacyIds::REDSTONE_ORE,
            BlockLegacyIds::LIT_REDSTONE_ORE,
            BlockLegacyIds::COAL_ORE,
            BlockLegacyIds::EMERALD_ORE,
            BlockLegacyIds::NETHER_QUARTZ_ORE,
            BlockLegacyIds::CARROT_BLOCK,
            BlockLegacyIds::POTATO_BLOCK,
        ])) {
            return 0;
        }
        $rand = mt_rand(0, 999);
        $plus = 0;
        switch ($level) {
            case 1:
                if (667 <= $rand) {
                    $plus = 1;
                }
                break;
            case 2:
                if (500 <= $rand && $rand < 750) {
                    $plus = 1;
                } elseif (750 <= $rand) {
                    $plus = 2;
                }
                break;
            case 3:
                if (400 <= $rand && $rand < 600) {
                    $plus = 1;
                } elseif (600 <= $rand && $rand < 800) {
                    $plus = 2;
                } elseif (800 <= $rand) {
                    $plus = 3;
                }
                break;
            default:
                break;
        }
        return $plus;
    }
}
