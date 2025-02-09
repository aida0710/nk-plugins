<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\BirchTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\TallBirchTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class BirchForestMountainsPopulator extends ForestPopulator {

    private const BIOMES = [BiomeIds::BIRCH_FOREST_MUTATED, BiomeIds::BIRCH_FOREST_HILLS_MUTATED];

    /** @var TreeDecoration[] */
    protected static array $TREES;

    protected static function initTrees() : void {
        self::$TREES = [
            new TreeDecoration(BirchTree::class, 1),
            new TreeDecoration(TallBirchTree::class, 1),
        ];
    }

    public function getBiomes() : ?array {
        return self::BIOMES;
    }

    protected function initPopulators() : void {
        $this->treeDecorator->setTrees(...self::$TREES);
    }
}

BirchForestMountainsPopulator::init();
