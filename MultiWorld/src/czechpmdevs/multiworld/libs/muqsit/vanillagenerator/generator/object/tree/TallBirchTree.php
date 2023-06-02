<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree;

use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;

class TallBirchTree extends BirchTree {

    public function __construct(Random $random, BlockTransaction $transaction) {
        parent::__construct($random, $transaction);
        $this->setHeight($this->height + $random->nextBoundedInt(7));
    }
}
