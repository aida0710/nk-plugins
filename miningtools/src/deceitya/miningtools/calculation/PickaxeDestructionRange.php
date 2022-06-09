<?php

namespace deceitya\miningtools\calculation;

use deceitya\miningtools\event\CountBlockEvent;
use deceitya\miningtools\Main;
use onebone\economyland\EconomyLand;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
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
        if ($item->getNamedTag()->getTag('MiningTools_Ultimate') === null) {//MiningTools_Ultimateがあった際は全部無視
            if (!in_array($block->getId(), $set['destructible'], true)) return [];
        }
        $radius = 0;
        if ($item->getNamedTag()->getTag('MiningTools_Expansion') !== null) {
            $nbt = $item->getNamedTag();
            $radius = $nbt->getInt("MiningTools_Expansion");
        }
        Main::$flag[$player->getName()] = true;
        for ($y = -1 - $radius; $y < 2 + $radius; $y++) {
            for ($x = -1 - $radius; $x < 2 + $radius; $x++) {
                for ($z = -1 - $radius; $z < 2 + $radius; $z++) {
                    $pos = $block->getPosition()->add($x, $y, $z);
                    $targetBlock = $block->getPosition()->getWorld()->getBlock($pos);
                    if ($targetBlock->getPosition()->getFloorY() <= 0) continue;
                    if ($item->getNamedTag()->getTag('MiningTools_Expansion') === null || $item->getNamedTag()->getTag('MiningTools_Ultimate') === null) {
                        //MiningTools_ExpansionとMiningTools_Ultimateが存在しない場合は無視できない
                        //あったら無視(範囲破壊内にあったブロック)
                        if (!in_array($targetBlock->getId(), $set['destructible'], true)) continue;
                    }
                    if (EconomyLand::getInstance()->posCheck($pos, $player) === false) continue;
                    switch ($targetBlock->getId()) {
                        case ItemIds::AIR:
                        case ItemIds::BEDROCK:
                        case ItemIds::BARRIER:
                        case ItemIds::WATER:
                        case ItemIds::FLOWING_WATER:
                        case ItemIds::WATER_LILY:
                        case ItemIds::MAGMA:
                            break;
                    }
                    $dropItems = array_merge($dropItems ?? [], (new ItemDrop())->getDrop($player, $targetBlock));
                    if (!$player->isSneaking()) {
                        if ($haveDurable) {
                            $this->toolDamageProcessing($player, $handItem);
                            if ($handItem->getDamage() >= $maxDurability - 15) {
                                $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                                break 3;
                            }
                            (new CountBlockEvent($player, $block))->call();
                            $block->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
                        }
                    } elseif ($pos->getFloorY() <= $player->getPosition()->getFloorY() - 1) {
                        continue;
                    } else {
                        if ($haveDurable) {
                            $this->toolDamageProcessing($player, $handItem);
                            if ($handItem->getDamage() >= $maxDurability - 15) {
                                $player->sendTitle("§c耐久が残り少しの為範囲採掘が適用されません", "§cかなとこ等を使用して修繕してください");
                                break 3;
                            }
                            (new CountBlockEvent($player, $block))->call();
                            $block->getPosition()->getWorld()->setBlock($pos, clone VanillaBlocks::AIR());
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