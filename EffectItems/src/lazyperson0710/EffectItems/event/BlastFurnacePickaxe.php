<?php

namespace lazyperson0710\EffectItems\event;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class BlastFurnacePickaxe implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('BlastFurnacePickaxe') !== null) {//BlastFurnacePickaxe
            foreach ($event->getDrops() as $item) {
                switch ($item->getId()) {
                    case BlockLegacyIds::COBBLESTONE:
                        $event->setDrops([VanillaBlocks::STONE()->asItem()]);
                        break;
                    case BlockLegacyIds::IRON_ORE:
                        $event->setDrops([VanillaItems::IRON_INGOT()]);
                        break;
                    case BlockLegacyIds::GOLD_ORE:
                        $event->setDrops([VanillaItems::GOLD_INGOT()]);
                        break;
                    case BlockLegacyIds::NETHERRACK:
                        $event->setDrops([VanillaItems::NETHER_BRICK()]);
                        break;
                    case BlockLegacyIds::SAND:
                        $event->setDrops([VanillaBlocks::GLASS()->asItem()]);
                        break;
                    case BlockLegacyIds::SPONGE:
                        $event->setDrops([VanillaBlocks::SPONGE()->asItem()]);
                        break;
                    case BlockLegacyIds::LOG:
                    case BlockLegacyIds::LOG2:
                        $event->setDrops([VanillaItems::COAL()]);
                        break;
                }
            }
        }
    }
}
