<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\MegaSpruceTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\RedwoodTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\TallRedwoodTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class MegaSpruceTaigaPopulator extends MegaTaigaPopulator {

	/** @var TreeDecoration[] */
	protected static array $TREES;

	protected static function initTrees() : void {
		self::$TREES = [
			new TreeDecoration(RedwoodTree::class, 44),
			new TreeDecoration(TallRedwoodTree::class, 22),
			new TreeDecoration(MegaSpruceTree::class, 33),
		];
	}

	public function getBiomes() : ?array {
		return [BiomeIds::REDWOOD_TAIGA_MUTATED, BiomeIds::REDWOOD_TAIGA_HILLS_MUTATED];
	}

	protected function initPopulators() : void {
		$this->treeDecorator->setTrees(...self::$TREES);
	}
}

MegaSpruceTaigaPopulator::init();
