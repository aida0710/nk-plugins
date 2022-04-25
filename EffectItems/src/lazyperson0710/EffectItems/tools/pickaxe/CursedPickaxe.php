<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\block\Air;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;

class CursedPickaxe implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('CursedPickaxe') !== null) {//CursedPickaxe
            foreach ($event->getDrops() as $drop) {
                if (mt_rand(1, 5) !== 5) {
                    return;
                }
                switch ($drop->getId()) {
                    case ItemIds::COAL:
                    case ItemIds::COAL_ORE:
                    case ItemIds::IRON_ORE:
                    case ItemIds::GOLD_ORE:
                    case ItemIds::REDSTONE:
                    case ItemIds::REDSTONE_ORE:
                    case VanillaItems::LAPIS_LAZULI()->getId():
                    case ItemIds::LAPIS_ORE:
                    case ItemIds::DIAMOND:
                    case ItemIds::DIAMOND_ORE:
                    case ItemIds::EMERALD:
                    case ItemIds::EMERALD_ORE:
                    case ItemIds::QUARTZ:
                    case ItemIds::QUARTZ_ORE:
                        $event->setDrops([VanillaItems::AIR()]);
                        $event->getPlayer()->sendMessage("呪いによって鉱石がなくなってしまった");
                        return;
                }
            }
        }
    }
}
