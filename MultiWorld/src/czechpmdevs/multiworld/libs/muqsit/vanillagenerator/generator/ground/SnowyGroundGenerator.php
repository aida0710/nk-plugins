<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\ground;

use pocketmine\block\VanillaBlocks;

class SnowyGroundGenerator extends GroundGenerator {

    public function __construct() {
        parent::__construct(VanillaBlocks::SNOW_LAYER());
    }
}
