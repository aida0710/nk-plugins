<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome;

class BiomeHeight {

    private float $height;
    private float $scale;

    public function __construct(float $height, float $scale) {
        $this->height = $height;
        $this->scale = $scale;
    }

    public function getHeight() : float {
        return $this->height;
    }

    public function getScale() : float {
        return $this->scale;
    }
}
