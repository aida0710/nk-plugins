<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree;

final class BigOakTreeLeafNode {

    public function __construct(
        public int $x,
        public int $y,
        public int $z,
        public int $branchY,
    ) {
    }
}
