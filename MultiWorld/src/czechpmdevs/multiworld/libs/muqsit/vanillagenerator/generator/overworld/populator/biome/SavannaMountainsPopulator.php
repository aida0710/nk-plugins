<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;

class SavannaMountainsPopulator extends SavannaPopulator {

    public function getBiomes() : ?array {
        return [BiomeIds::SAVANNA_MUTATED, BiomeIds::SAVANNA_PLATEAU_MUTATED];
    }

    protected function initPopulators() : void {
        $this->treeDecorator->setAmount(2);
        $this->treeDecorator->setTrees(...self::$TREES);
        $this->flowerDecorator->setAmount(2);
        $this->flowerDecorator->setFlowers(...self::$FLOWERS);
        $this->tallGrassDecorator->setAmount(5);
    }
}

SavannaMountainsPopulator::init();
