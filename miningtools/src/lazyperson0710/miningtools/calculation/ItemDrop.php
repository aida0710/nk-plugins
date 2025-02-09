<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\calculation;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\miningTools\AndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\CobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\DioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\IronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\SandToGlassSetting;
use lazyperson710\core\listener\DirectInventory;
use lazyperson710\core\listener\FortuneListener;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use function count;

class ItemDrop {

    /**
     * @param Player $player
     * @param Block  $block
     * @return array
     */
    public function getDrop(Player $player, Block $block) : array {
        $item = $player->getInventory()->getItemInHand();
        if ($item->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE)) !== null) {
            $drops = $block->getDrops($item);
            if (empty($drops)) {
                return $block->getDrops($item);
            }
            $plus = FortuneListener::Calculation($block, $item->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE))->getLevel());
            $drops[0]->setCount($drops[0]->getCount() + $plus);
            return $this->checkMiningSettings($player, $drops);
        }
        return $this->checkMiningSettings($player, $block->getDrops($item));
    }

    /**
     * @param Player $player
     * @param Item[] $drops
     * @return array
     */
    public function checkMiningSettings(Player $player, array $drops) : array {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        $result = [];
        foreach ($drops as $drop) {
            switch ($drop) {
                case VanillaBlocks::GRASS()->asItem():
                    if ($setting->getSetting(GrassToDirtSetting::getName())?->getValue()) {
                        $drop = VanillaBlocks::DIRT()->asItem()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::COBBLESTONE()->asItem():
                    if ($setting->getSetting(CobblestoneToStoneSetting::getName())?->getValue()) {
                        $drop = VanillaBlocks::STONE()->asItem()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::GRANITE()->asItem():
                    if ($setting->getSetting(GraniteToStoneSetting::getName())?->getValue()) {
                        $drop = VanillaBlocks::STONE()->asItem()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::DIORITE()->asItem():
                    if ($setting->getSetting(DioriteToStoneSetting::getName())?->getValue()) {
                        $drop = VanillaBlocks::STONE()->asItem()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::ANDESITE()->asItem():
                    if ($setting->getSetting(AndesiteToStoneSetting::getName())?->getValue()) {
                        $drop = VanillaBlocks::STONE()->asItem()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::SAND()->asItem():
                case VanillaBlocks::RED_SAND()->asItem():
                    if ($setting->getSetting(SandToGlassSetting::getName())?->getValue()) {
                        $drop = VanillaBlocks::GLASS()->asItem()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::IRON_ORE()->asItem():
                    if ($setting->getSetting(IronIngotSetting::getName())?->getValue()) {
                        $drop = VanillaItems::IRON_INGOT()->setCount($drop->getCount());
                    }
                    break;
                case VanillaBlocks::GOLD_ORE()->asItem():
                    if ($setting->getSetting(GoldIngotSetting::getName())?->getValue()) {
                        $drop = VanillaItems::GOLD_INGOT()->setCount($drop->getCount());
                    }
                    break;
                default:
                    break;
            }
            $result[] = $drop;
        }
        return $result;
    }

    public function DropItem(Player $player, BlockBreakEvent $event, array $dropItems) : void {
        if (empty($dropItems)) {
            return;
        }
        //$dropItems = array_diff($dropItems, [$startBlock]);
        //$dropItems = array_values($dropItems);
        //$dropItems = $player->getInventory()->addItem(...$dropItems);
        if (count($dropItems) === 0) {
            $event->setDrops([]);
        } else {
            DirectInventory::onDrop($player, $dropItems);
        }
    }

}
