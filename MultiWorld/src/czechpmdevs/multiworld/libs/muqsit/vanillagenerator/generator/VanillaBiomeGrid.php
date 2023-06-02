<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid\BiomeGrid;
use function array_key_exists;

class VanillaBiomeGrid implements BiomeGrid {

    /** @var int[] */
    public array $biomes = [];

    public function getBiome(int $x, int $z) : ?int {
        // upcasting is very important to get extended biomes
        return array_key_exists($hash = $x | $z << 4, $this->biomes) ? $this->biomes[$hash] & 0xFF : null;
    }

    public function setBiome(int $x, int $z, int $biomeId) : void {
        $this->biomes[$x | $z << 4] = $biomeId;
    }
}
