<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\event;

use lazyperson0710\shop\object\ItemShopObject;
use pocketmine\event\Event;
use pocketmine\player\Player;

class ItemShopBuyEvent extends Event {

    public function __construct(
        private readonly Player $player,
        private readonly ItemShopObject $item,
        private readonly int $count,
        private readonly int $price,
    ) {
    }

}
