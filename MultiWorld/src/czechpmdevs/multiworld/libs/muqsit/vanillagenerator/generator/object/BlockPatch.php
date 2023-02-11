<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object;

use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use function array_key_exists;

class BlockPatch extends TerrainObject {

	private const MIN_RADIUS = 2;

	private Block $type;
	private int $horizRadius;
	private int $vertRadius;

	/** @var int[] */
	private array $overridables = [];

	/**
	 * Creates a patch.
	 *
	 * @param Block $type        the ground cover block type
	 * @param int   $horizRadius the maximum radius on the horizontal plane
	 * @param int   $vertRadius  the depth above and below the center
	 */
	public function __construct(Block $type, int $horizRadius, int $vertRadius, int ...$overridablesFullId) {
		$this->type = $type;
		$this->horizRadius = $horizRadius;
		$this->vertRadius = $vertRadius;
		foreach ($overridablesFullId as $fullId) {
			$this->overridables[$fullId] = $fullId;
		}
	}

	public function generate(ChunkManager $world, Random $random, int $sourceX, int $sourceY, int $sourceZ) : bool {
		$succeeded = false;
		$n = $random->nextBoundedInt($this->horizRadius - self::MIN_RADIUS) + self::MIN_RADIUS;
		$nsquared = $n * $n;
		for ($x = $sourceX - $n; $x <= $sourceX + $n; ++$x) {
			for ($z = $sourceZ - $n; $z <= $sourceZ + $n; ++$z) {
				if (($x - $sourceX) * ($x - $sourceX) + ($z - $sourceZ) * ($z - $sourceZ) > $nsquared) {
					continue;
				}
				for ($y = $sourceY - $this->vertRadius; $y <= $sourceY + $this->vertRadius; ++$y) {
					$block = $world->getBlockAt($x, $y, $z);
					if (!array_key_exists($block->getFullId(), $this->overridables)) {
						continue;
					}
					if (TerrainObject::killWeakBlocksAbove($world, $x, $y, $z)) {
						break;
					}
					$world->setBlockAt($x, $y, $z, $this->type);
					$succeeded = true;
					break;
				}
			}
		}
		return $succeeded;
	}
}
