<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\noise\glowstone;

// Inner class to speed up gradient computations
// (array access is a lot slower than member access)
final class Grad {

    public function __construct(
        public float $x,
        public float $y,
        public float $z
    ) {
    }
}
