<?php

namespace deceitya\miningtools\calculation;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\player\Player;

class ItemDrop {

    /**
     * @param Player $player
     * @param Block $block
     * @return item[]
     */
    public function getDrop(Player $player, Block $block): array {
        $item = $player->getInventory()->getItemInHand();
        return $block->getDrops($item);
    }

    /**
     * @param Player $player
     * @param BlockBreakEvent $event
     * @param array $dropItems
     * @param Block $startBlock
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