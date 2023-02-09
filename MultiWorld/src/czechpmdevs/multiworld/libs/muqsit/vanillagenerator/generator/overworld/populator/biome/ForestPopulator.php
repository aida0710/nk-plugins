<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\DoubleTallPlant;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\BirchTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\GenericTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;
use pocketmine\block\DoublePlant;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use function count;

class ForestPopulator extends BiomePopulator {

	private const BIOMES = [BiomeIds::FOREST, BiomeIds::FOREST_HILLS];

	/** @var TreeDecoration[] */
	protected static array $TREES;

	/** @var DoublePlant[] */
	private static array $DOUBLE_PLANTS;

	public static function init() : void {
		parent::init();
		self::$DOUBLE_PLANTS = [
			VanillaBlocks::LILAC(),
			VanillaBlocks::ROSE_BUSH(),
			VanillaBlocks::PEONY(),
		];
	}

	protected static function initTrees() : void {
		self::$TREES = [
			new TreeDecoration(GenericTree::class, 4),
			new TreeDecoration(BirchTree::class, 1),
		];
	}

	protected int $doublePlantLoweringAmount = 3;

	protected function initPopulators() : void {
		$this->doublePlantDecorator->setAmount(0);
		$this->treeDecorator->setAmount(10);
		$this->treeDecorator->setTrees(...self::$TREES);
		$this->tallGrassDecorator->setAmount(2);
	}

	public function getBiomes() : ?array {
		return self::BIOMES;
	}

	public function populateOnGround(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
		$sourceX = $chunkX << 4;
		$sourceZ = $chunkZ << 4;
		$amount = $random->nextBoundedInt(5) - $this->doublePlantLoweringAmount;
		$i = 0;
		while ($i < $amount) {
			for ($j = 0; $j < 5; ++$j, ++$i) {
				$x = $random->nextBoundedInt(16);
				$z = $random->nextBoundedInt(16);
				$y = $random->nextBoundedInt($chunk->getHighestBlockAt($x, $z) + 32);
				$species = self::$DOUBLE_PLANTS[$random->nextBoundedInt(count(self::$DOUBLE_PLANTS))];
				if ((new DoubleTallPlant($species))->generate($world, $random, $sourceX + $x, $y, $sourceZ + $z)) {
					++$i;
					break;
				}
			}
		}
		parent::populateOnGround($world, $random, $chunkX, $chunkZ, $chunk);
	}
}

ForestPopulator::init();
