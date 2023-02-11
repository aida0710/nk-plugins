<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\BigOakTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\CocoaTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class JungleEdgePopulator extends JunglePopulator {

	/** @var TreeDecoration[] */
	protected static array $TREES;

	protected static function initTrees() : void {
		self::$TREES = [
			new TreeDecoration(BigOakTree::class, 10),
			new TreeDecoration(CocoaTree::class, 45),
		];
	}

	protected function initPopulators() : void {
		$this->treeDecorator->setAmount(2);
		$this->treeDecorator->setTrees(...self::$TREES);
	}

	public function getBiomes() : ?array {
		return [BiomeIds::JUNGLE_EDGE, BiomeIds::JUNGLE_EDGE_MUTATED];
	}
}

JungleEdgePopulator::init();
