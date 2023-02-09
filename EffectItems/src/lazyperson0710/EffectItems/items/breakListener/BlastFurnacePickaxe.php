<?php

declare(strict_types = 1);
namespace lazyperson0710\EffectItems\items\breakListener;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\VanillaItems;

class BlastFurnacePickaxe {

	public static function execution(BlockBreakEvent $event) : void {
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
