<?php

namespace deceitya\luckyenchant;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public const TARGET = [16, 56, 129, 153, 21];

    public function onEnable(): void {
        $enchant = new Enchantment(
            '幸運',
            Rarity::RARE,
            ItemFlags::DIG,
            ItemFlags::SHEARS,
            3
        );
        EnchantmentIdMap::getInstance()->register(EnchantmentIds::FORTUNE, $enchant);
        StringToEnchantmentParser::getInstance()->register("fortune", fn() => $enchant);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        srand();
    }

    /**
     * @priority LOW
     */
    public function onBreak(BlockBreakEvent $event) {
        $enchant = $event->getItem()->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE));
        if ($enchant === null) {
            return;
        }
        if (!in_array($event->getBlock()->getId(), [56, 21, 73, 16, 129, 153, -288, 89])) {
            return;
        }
        $rand = mt_rand(0, 999);
        $plus = 0;
        switch ($enchant->getLevel()) {
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
        $item = $event->getDrops()[0];
        $item->setCount($item->getCount() + $plus);
    }
}
