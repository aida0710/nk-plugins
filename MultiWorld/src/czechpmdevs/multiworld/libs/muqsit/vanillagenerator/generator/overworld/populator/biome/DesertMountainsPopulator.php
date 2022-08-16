<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;

class DesertMountainsPopulator extends DesertPopulator {

    protected function initPopulators(): void {
        $this->waterLakeDecorator->setAmount(1);
    }

    public function getBiomes(): ?array {
        return [BiomeIds::DESERT_MUTATED];
    }
}
