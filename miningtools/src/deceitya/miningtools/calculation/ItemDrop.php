<?php

namespace deceitya\miningtools\calculation;

use lazyperson710\core\listener\FortuneListener;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\player\Player;

class ItemDrop {

    /**
     * @param Player $player
     * @param Block  $block
     * @return array
     */
    public function getDrop(Player $player, Block $block): array {
        $item = $player->getInventory()->getItemInHand();
        $enchant = $item->getEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE));
        if ($enchant !== null) {
            $drops = $block->getDrops($item);
            if (empty($drops)) {
                return $block->getDrops($item);
            }
            $plus = FortuneListener::Calculation($block, $enchant->getLevel());
            $drops[0]->setCount($drops[0]->getCount() + $plus);
            return $drops;
        }
        return $block->getDrops($item);
    }

    /**
     * @param Player          $player
     * @param BlockBreakEvent $event
     * @param array           $dropItems
     * @param Block           $startBlock
     * @return void
     */
    public function DropItem(Player $player, BlockBreakEvent $event, array $dropItems, Block $startBlock): void {
        if (empty($dropItems)) {
            return;
        }
        $dropItems = array_diff($dropItems, [$startBlock]);
        $dropItems = array_values($dropItems);
        $dropItems = $player->getInventory()->addItem(...$dropItems);
        if (count($dropItems) === 0) {
            $event->setDropsVariadic(VanillaBlocks::AIR()->asItem());
        } else {
            $event->setDrops($dropItems);
        }
    }

}