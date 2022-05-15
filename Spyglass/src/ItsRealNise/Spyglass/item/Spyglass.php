<?php

namespace ItsRealNise\Spyglass\item;

use ItsRealNise\Spyglass\CustomIds;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class Spyglass extends Item {

    public function __construct() {
        parent::__construct(new ItemIdentifier(CustomIds::SPYGLASS, 0), "Spyglass");
    }

    public function getMaxStackSize(): int {
        return 1;
    }
}
