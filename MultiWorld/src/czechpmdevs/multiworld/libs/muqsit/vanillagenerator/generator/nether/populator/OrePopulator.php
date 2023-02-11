<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\nether\populator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\OreType;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome\OrePopulator as OverworldOrePopulator;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\World;

class OrePopulator extends OverworldOrePopulator {

	/**
	 * @noinspection MagicMethodsValidityInspection
	 * @noinspection PhpMissingParentConstructorInspection
	 */
	public function __construct(int $worldHeight = World::Y_MAX) {
		$this->addOre(new OreType(VanillaBlocks::NETHER_QUARTZ_ORE(), 10, $worldHeight - (10 * ($worldHeight >> 7)), 13, BlockLegacyIds::NETHERRACK), 16);
		$this->addOre(new OreType(VanillaBlocks::MAGMA(), 26, 32 + (5 * ($worldHeight >> 7)), 32, BlockLegacyIds::NETHERRACK), 16);
	}
}
