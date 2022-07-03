<?php

namespace deceitya\miningtools\calculation;

use deceitya\miningtools\event\MiningToolsBreakEvent;
use deceitya\miningtools\Main;
use onebone\economyland\EconomyLand;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class PickaxeDestructionRange {

    /**
     * @param Player $player
     * @param Block $block
     * @param Item $item
     * @param bool $haveDurable
     * @param Item $handItem
     * @param array $set
     * @param array $dropItems
     * @return array
     */
    public function PickaxeDestructionRange(Player $player, Block $block, Item $item, bool $haveDurable, Item $handItem, array $set, array $dropItems): array {
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') === null) {
            if (!in_array($block->getId(), $set['destructible'], true)) return [];
        }
        $radius = 0;
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
            $radius = $item->getNamedTag()->getInt("MiningTools_Expansion_Range");
        }
        Main::$flag[$player->getName()] = true;
        for ($y = -1 - $radius; $y < 2 + $radius; $y++) {
            for ($x = -1 - $radius; $x < 2 + $radius; $x++) {
                for ($z = -1 - $radius; $z < 2 + $radius; $z++) {
                    $pos = $block->getPosition()->add($x, $y, $z);
                    $targetBlock = $block->getPosition()->getWorld()->getBlock($pos);
                    switch ($targetBlock->getId()) {
                        case BlockLegacyIds::AIR:
                        case BlockLegacyIds::BEDROCK:
                        case BlockLegacyIds::BARRIER:
                        case BlockLegacyIds::WATER:
                        case BlockLegacyIds::FLOWING_WATER:
                        case BlockLegacyIds::WATER_LILY:
                        case BlockLegacyIds::LAVA:
                        case BlockLegacyIds::FLOWING_LAVA:
                        case BlockLegacyIds::CRAFTING_TABLE:
                        case BlockLegacyIds::HOPPER_BLOCK:
                            ##sign
                        case BlockLegacyIds::SIGN_POST:
                        case BlockLegacyIds::WALL_SIGN:
                        case BlockLegacyIds::SPRUCE_STANDING_SIGN:
                        case BlockLegacyIds::SPRUCE_WALL_SIGN:
                        case BlockLegacyIds::BIRCH_STANDING_SIGN:
                        case BlockLegacyIds::BIRCH_WALL_SIGN:
                        case BlockLegacyIds::JUNGLE_STANDING_SIGN:
                        case BlockLegacyIds::JUNGLE_WALL_SIGN:
                        case BlockLegacyIds::ACACIA_STANDING_SIGN:
                        case BlockLegacyIds::ACACIA_WALL_SIGN:
                        case BlockLegacyIds::DARKOAK_STANDING_SIGN:
                        case BlockLegacyIds::DARKOAK_WALL_SIGN:
                            ##chest
                        case BlockLegacyIds::CHEST:
                        case BlockLegacyIds::ENDER_CHEST:
                        case BlockLegacyIds::TRAPPED_CHEST:
                        case BlockLegacyIds::BARREL:
                        case BlockLegacyIds::SHULKER_BOX:
                        case BlockLegacyIds::UNDYED_SHULKER_BOX:
                            ##furnace
                        case BlockLegacyIds::FURNACE:
                        case BlockLegacyIds::LIT_FURNACE:
                        case BlockLegacyIds::BLAST_FURNACE:
                        case BlockLegacyIds::LIT_BLAST_FURNACE:
                        case BlockLegacyIds::SMOKER:
                        case BlockLegacyIds::LIT_SMOKER:
                            continue 2;
                    }
                    if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') === null) {
                        if (!in_array($targetBlock->getId(), $set['destructible'], true)) continue;
                    } elseif ($item->getNamedTag()->getInt('MiningTools_Expansion_Range') !== 3) {
                        if (!in_array($targetBlock->getId(), $set['destructible'], true)) continue;
                    }
                    if ($targetBlock->getPosition()->getFloorY() <= 0) continue;
                    if (EconomyLand::getInstance()->posCheck($pos, $player) === false) continue;
                    $blockIds[] = $targetBlock->getId();
                    $dropItems = array_merge($dropItems ?? [], (new ItemDrop())->getDrop($player, $targetBlock));
                    if (!$player->isSneaking()) {
                        if ($haveDurable) {
                            $this->toolDamageProcessing($player, $handItem);
                            (new MiningToolsBreakEvent($player, $targetBlock))->call();
                            $targetBlock->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                        }
                    } elseif ($pos->getFloorY() <= $player->getPosition()->getFloorY() - 1) {
                        continue;
                    } else {
                        if ($haveDurable) {
                            $this->toolDamageProcessing($player, $handItem);
                            (new MiningToolsBreakEvent($player, $targetBlock))->call();
                            $targetBlock->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                        }
                    }
                }
            }
        }
        Main::$flag[$player->getName()] = false;
        return $dropItems;
    }

    /**
     * @param Player $player
     * @param $handItem
     * @return void
     */
    public function toolDamageProcessing(Player $player, $handItem): void {
        if ($player->getGamemode() !== GameMode::CREATIVE()) {
            /** @var Durable $handItem */
            $handItem->applyDamage(1);
            $player->getInventory()->setItemInHand($handItem);
        }
    }
}