<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;

class DesertPopulator extends BiomePopulator {

    public function getBiomes() : ?array {
        return [BiomeIds::DESERT, BiomeIds::DESERT_HILLS];
    }

    protected function initPopulators() : void {
        $this->waterLakeDecorator->setAmount(0);
        $this->deadBushDecorator->setAmount(2);
        $this->sugarCaneDecorator->setAmount(60);
        $this->cactusDecorator->setAmount(10);
    }
}
