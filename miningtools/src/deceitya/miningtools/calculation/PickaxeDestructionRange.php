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
     * @param $maxDurability
     * @param array $set
     * @return array
     */
    public function PickaxeDestructionRange(Player $player, Block $block, Item $item, bool $haveDurable, Item $handItem, $maxDurability, array $set): array {
        $dropItems = [];
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') === null) {
            if (!in_array($block->getId(), $set['destructible'], true)) return [];
        }
        $radius = 0;
        if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
            $nbt = $item->getNamedTag();
            $radius = $nbt->getInt("MiningTools_Expansion_Range");
        }
        Main::$flag[$player->getName()] = true;
        var_dump(__LINE__ . "行目は実行されました");
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
                        case BlockLegacyIds::MAGMA:
                            break 2;
                    }
                    //var_dump("§a" . $targetBlock->getId() . "§r");
                    //if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') === null) {
                    //    var_dump(__LINE__ . "行目は実行されましたeeeeeeee");
                    //    if (!in_array($targetBlock->getId(), $set['destructible'], true)) continue;
                    //} elseif ($item->getNamedTag()->getInt('MiningTools_Expansion_Range') !== 3) {
                    //    var_dump(__LINE__ . "行目は実行されましたbbbbbbbbbbbbbbbbbbbbbbbb");
                    //    if (!in_array($targetBlock->getId(), $set['destructible'], true)) continue;
                    //} else {
                    //    var_dump(__LINE__ . "行目は実行されましたsssssssssssssssssssssssssss");
                    //}
                    if (!in_array($targetBlock->getId(), $set['destructible'], true)) continue;
                    if ($targetBlock->getPosition()->getFloorY() <= 0) continue;
                    if (EconomyLand::getInstance()->posCheck($pos, $player) === false) continue;
                    $dropItems = array_merge($dropItems ?? [], (new ItemDrop())->getDrop($player, $targetBlock));
                    if (!$player->isSneaking()) {
                        if ($haveDurable) {
                            $this->toolDamageProcessing($player, $handItem);
                            /** @var Durable $handItem */
                            if ($handItem->getDamage() >= $maxDurability - 15) {
                                $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                                break 3;
                            }
                            (new MiningToolsBreakEvent($player, $targetBlock))->call();
                            $targetBlock->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                        }
                    } elseif ($pos->getFloorY() <= $player->getPosition()->getFloorY() - 1) {
                        continue;
                    } else {
                        if ($haveDurable) {
                            $this->toolDamageProcessing($player, $handItem);
                            /** @var Durable $handItem */
                            if ($handItem->getDamage() >= $maxDurability - 15) {
                                $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                                break 3;
                            }
                            (new MiningToolsBreakEvent($player, $targetBlock))->call();
                            $targetBlock->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                        }
                    }
                }
            }
        }
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