<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\RedwoodTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class IcePlainsPopulator extends BiomePopulator {

    /** @var TreeDecoration[] */
    protected static array $TREES;

    public function getBiomes() : ?array {
        return [BiomeIds::ICE_PLAINS, BiomeIds::ICE_MOUNTAINS];
    }

    protected function initPopulators() : void {
        $this->treeDecorator->setAmount(1);
        $this->treeDecorator->setTrees(...self::$TREES);
        $this->flowerDecorator->setAmount(0);
    }

    protected static function initTrees() : void {
        self::$TREES = [
            new TreeDecoration(RedwoodTree::class, 1),
        ];
    }
}

IcePlainsPopulator::init();
