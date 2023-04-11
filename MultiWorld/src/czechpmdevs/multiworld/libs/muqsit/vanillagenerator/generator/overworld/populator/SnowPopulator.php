<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeClimateManager;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\Populator;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use function in_array;

class SnowPopulator implements Populator {

	public function populate(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
		$disallowedBlocks = [
			VanillaBlocks::WATER()->getFullId(),
			VanillaBlocks::WATER()->setStill()->getFullId(),
			VanillaBlocks::SNOW()->getFullId(),
			VanillaBlocks::ICE()->getFullId(),
			VanillaBlocks::PACKED_ICE()->getFullId(),
			VanillaBlocks::DANDELION()->getFullId(),
			VanillaBlocks::POPPY()->getFullId(),
			VanillaBlocks::DOUBLE_TALLGRASS()->getFullId(),
			VanillaBlocks::LARGE_FERN()->getFullId(),
			VanillaBlocks::BROWN_MUSHROOM()->getFullId(),
			VanillaBlocks::RED_MUSHROOM()->getFullId(),
			VanillaBlocks::ROSE_BUSH()->getFullId(),
			VanillaBlocks::LARGE_FERN()->getFullId(),
			VanillaBlocks::SUGARCANE()->getFullId(),
			VanillaBlocks::TALL_GRASS()->getFullId(),
			VanillaBlocks::LAVA()->getFullId(),
			VanillaBlocks::LAVA()->setStill()->getFullId(),
		];
		$dirt = VanillaBlocks::DIRT()->getFullId();
		$grass = VanillaBlocks::GRASS()->getFullId();
		$snow = VanillaBlocks::SNOW_LAYER()->getFullId();
		$sourceX = $chunkX << 4;
		$sourceZ = $chunkZ << 4;
		for ($x = 0; $x < 16; ++$x) {
			for ($z = 0; $z < 16; ++$z) {
				$y = ($chunk->getHighestBlockAt($x, $z) ?? 0);
				if (BiomeClimateManager::isSnowy($chunk->getBiomeId($x, $z), $sourceX + $x, $y, $sourceZ + $z)) {
					$block = $chunk->getFullBlock($x, $y, $z);
					if (in_array($block, $disallowedBlocks, true)) {
						continue;
					}
					if ($block === $dirt) {
						$chunk->setFullBlock($x, $y, $z, $grass);
					}
					$chunk->setFullBlock($x, $y + 1, $z, $snow);
				}
			}
		}
	}
}
