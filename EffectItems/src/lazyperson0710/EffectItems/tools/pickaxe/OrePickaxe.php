<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\block\BlockLegacyIds;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class OrePickaxe implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('OrePickaxe') !== null) {//OrePickaxe
            if ($event->getPlayer()->getInventory()->getItemInHand()->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::UNBREAKING))) {
                $event->cancel();
                $event->getPlayer()->sendMessage("§bEnchant §7>> §cこのアイテムにはシルクタッチは使用できません");
            }
            switch ($event->getBlock()->getId()) {
                case BlockLegacyIds::COAL_ORE:
                case BlockLegacyIds::NETHER_QUARTZ_ORE:
                    break;
                case BlockLegacyIds::IRON_ORE:
                case BlockLegacyIds::GOLD_ORE:
                    //1
                    break;
                case BlockLegacyIds::LAPIS_ORE:
                case BlockLegacyIds::REDSTONE_ORE:
                case BlockLegacyIds::LIT_REDSTONE_ORE:
                    //2
                    break;
                case BlockLegacyIds::DIAMOND_ORE:
                    //3
                    break;
                case BlockLegacyIds::EMERALD_ORE:
                    if (rand(1, 15) === 3) {
                        //何かやりたい
                    }
                    break;
            }
        }
    }
}
