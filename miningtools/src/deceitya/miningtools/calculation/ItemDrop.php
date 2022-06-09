<?php

namespace deceitya\miningtools\calculation;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\Event;
use pocketmine\player\Player;

class ItemDrop {

    /**
     * @param Player $player
     * @param Block $block
     * @return array
     */
    public function getDrop(Player $player, Block $block): array {
        $item = $player->getInventory()->getItemInHand();
        return $block->getDrops($item);
    }

    /**
     * @param Player $player
     * @param Event $event
     * @param $dropItems
     * @param $startBlock
     * @return void
     */
    public function DropItem(Player $player, Event $event, $dropItems, $startBlock): void {
        if (is_null($dropItems)) {
            return;
        }
        $dropItems = array_diff($dropItems, array($startBlock));
        $dropItems = array_values($dropItems);
        $dropItems = $player->getInventory()->addItem(...$dropItems);
        if (count($dropItems) === 0) {
            $event->setDropsVariadic(VanillaBlocks::AIR()->asItem());
        } else {
            $event->setDrops($dropItems);
        }
    }
}