<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\RedwoodTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class IcePlainsPopulator extends BiomePopulator {

    /** @var TreeDecoration[] */
    protected static array $TREES;

    protected static function initTrees(): void {
        self::$TREES = [
            new TreeDecoration(RedwoodTree::class, 1),
        ];
    }

    public function getBiomes(): ?array {
        return [BiomeIds::ICE_PLAINS, BiomeIds::ICE_MOUNTAINS];
    }

    protected function initPopulators(): void {
        $this->tree_decorator->setAmount(1);
        $this->tree_decorator->setTrees(...self::$TREES);
        $this->flower_decorator->setAmount(0);
    }
}

IcePlainsPopulator::init();