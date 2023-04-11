<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\Decorator;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Dirt;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use function assert;

class MushroomDecorator extends Decorator {

	private Block $type;

	private bool $fixedHeightRange = false;

	private float $density = 0.0;

	/**
	 * Creates a mushroom decorator for the overworld.
	 *
	 * @param Block $type {@link Material#BROWN_MUSHROOM} or {@link Material#RED_MUSHROOM}
	 */
	public function __construct(Block $type) {
		$this->type = $type;
	}

	public function setUseFixedHeightRange() : MushroomDecorator {
		$this->fixedHeightRange = true;
		return $this;
	}

	public function setDensity(float $density) : MushroomDecorator {
		$this->density = $density;
		return $this;
	}

	public function decorate(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
		if ($random->nextFloat() < $this->density) {
			$sourceX = ($chunkX << 4) + $random->nextBoundedInt(16);
			$sourceZ = ($chunkZ << 4) + $random->nextBoundedInt(16);
			$sourceY = $chunk->getHighestBlockAt($sourceX & 0x0f, $sourceZ & 0x0f);
			$sourceY = $this->fixedHeightRange ? $sourceY : $random->nextBoundedInt($sourceY << 1);
			$height = $world->getMaxY();
			for ($i = 0; $i < 64; ++$i) {
				$x = $sourceX + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
				$z = $sourceZ + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
				$y = $sourceY + $random->nextBoundedInt(4) - $random->nextBoundedInt(4);
				$block = $world->getBlockAt($x, $y, $z);
				$belowBelow = $world->getBlockAt($x, $y - 1, $z);
				if ($y < $height && $block->getId() === BlockLegacyIds::AIR) {
					switch ($belowBelow->getId()) {
						case BlockLegacyIds::MYCELIUM:
						case BlockLegacyIds::PODZOL:
							$canPlaceShroom = true;
							break;
						case BlockLegacyIds::GRASS:
							$canPlaceShroom = ($block->getLightLevel() < 13);
							break;
						case BlockLegacyIds::DIRT:
							assert($belowBelow instanceof Dirt);
							if (!$belowBelow->isCoarse()) {
								$canPlaceShroom = $block->getLightLevel() < 13;
							} else {
								$canPlaceShroom = false;
							}
							break;
						default:
							$canPlaceShroom = false;
					}
					if ($canPlaceShroom) {
						$world->setBlockAt($x, $y, $z, $this->type);
					}
				}
			}
		}
	}
}
