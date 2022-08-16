<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\BirchTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\BrownMushroomTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\DarkOakTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\GenericTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\RedMushroomTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class RoofedForestPopulator extends ForestPopulator {

    private const BIOMES = [BiomeIds::ROOFED_FOREST, BiomeIds::ROOFED_FOREST_MUTATED];

    /** @var TreeDecoration[] */
    protected static array $TREES;

    protected static function initTrees(): void {
        self::$TREES = [
            new TreeDecoration(GenericTree::class, 20),
            new TreeDecoration(BirchTree::class, 5),
            new TreeDecoration(RedMushroomTree::class, 2),
            new TreeDecoration(BrownMushroomTree::class, 2),
            new TreeDecoration(DarkOakTree::class, 50),
        ];
    }

    protected function initPopulators(): void {
        $this->treeDecorator->setAmount(50);
        $this->treeDecorator->setTrees(...self::$TREES);
        $this->tallGrassDecorator->setAmount(4);
    }

    public function getBiomes(): ?array {
        return self::BIOMES;
    }
}

RoofedForestPopulator::init();
