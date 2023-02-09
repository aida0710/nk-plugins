<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\BirchTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class BirchForestPopulator extends ForestPopulator {

	private const BIOMES = [BiomeIds::BIRCH_FOREST, BiomeIds::BIRCH_FOREST_HILLS];

	/** @var TreeDecoration[] */
	protected static array $TREES;

	protected static function initTrees() : void {
		self::$TREES = [
			new TreeDecoration(BirchTree::class, 1),
		];
	}

	protected function initPopulators() : void {
		$this->treeDecorator->setAmount(10);
		$this->treeDecorator->setTrees(...self::$TREES);
		$this->tallGrassDecorator->setAmount(2);
	}

	public function getBiomes() : ?array {
		return self::BIOMES;
	}
}

BirchForestPopulator::init();
