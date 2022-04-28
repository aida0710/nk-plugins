<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

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
                        $this->addFire($player);
                        $event->setDrops([VanillaBlocks::STONE()->asItem()]);
                        break;
                    case BlockLegacyIds::IRON_ORE:
                        $this->addFire($player);
                        $event->setDrops([VanillaItems::IRON_INGOT()]);
                        break;
                    case BlockLegacyIds::GOLD_ORE:
                        $this->addFire($player);
                        $event->setDrops([VanillaItems::GOLD_INGOT()]);
                        break;
                    case BlockLegacyIds::NETHERRACK:
                        $this->addFire($player);
                        $event->setDrops([VanillaItems::NETHER_BRICK()]);
                        break;
                    case BlockLegacyIds::SAND:
                        $this->addFire($player);
                        $event->setDrops([VanillaBlocks::GLASS()->asItem()]);
                        break;
                    case BlockLegacyIds::SPONGE:
                        $this->addFire($player);
                        $event->setDrops([VanillaBlocks::SPONGE()->asItem()]);
                        break;
                    case BlockLegacyIds::LOG:
                    case BlockLegacyIds::LOG2:
                        $this->addFire($player);
                        $event->setDrops([VanillaItems::COAL()]);
                        break;
                }
            }
        }
    }

    public function addFire(Player $player) {
        if ($player->isOnFire()) {
            return;
        }
        if (mt_rand(1, 350) === 350) {
            $player->setOnFire(5);
            $player->sendMessage("自分も燃えてしまった");
        }
    }
}
