<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\nether\decorator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\Decorator;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use function array_key_exists;

class MushroomDecorator extends Decorator {

	/** @var int[] */
	private static array $MATERIALS;

	public static function init() : void {
		self::$MATERIALS = [];
		foreach ([BlockLegacyIds::NETHERRACK, BlockLegacyIds::QUARTZ_ORE, BlockLegacyIds::SOUL_SAND, BlockLegacyIds::GRAVEL] as $blockId) {
			self::$MATERIALS[$blockId] = $blockId;
		}
	}

	private Block $type;

	public function __construct(Block $type) {
		$this->type = $type;
	}

	public function decorate(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
		$height = $world->getMaxY();
		$sourceX = ($chunkX << 4) + $random->nextBoundedInt(16);
		$sourceZ = ($chunkZ << 4) + $random->nextBoundedInt(16);
		$sourceY = $random->nextBoundedInt($height);
		for ($i = 0; $i < 64; ++$i) {
			$x = $sourceX + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
			$z = $sourceZ + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
			$y = $sourceY + $random->nextBoundedInt(4) - $random->nextBoundedInt(4);
			$block = $world->getBlockAt($x, $y, $z);
			$blockBelow = $world->getBlockAt($x, $y - 1, $z);
			if (
				$y < $height &&
				$block->getId() === BlockLegacyIds::AIR &&
				array_key_exists($blockBelow->getId(), self::$MATERIALS)
			) {
				$world->setBlockAt($x, $y, $z, $this->type);
			}
		}
	}
}

MushroomDecorator::init();
