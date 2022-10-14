<?php

namespace lazyperson0710\ShopAPI\event;

use lazyperson0710\ShopAPI\object\LevelShopItem;
use pocketmine\event\Event;
use pocketmine\player\Player;

class LevelShopClosingEvent extends Event {

    public function __construct(
        private Player        $player,
        private LevelShopItem $item,
    ) {
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function getItem(): LevelShopItem {
        return $this->item;
    }

}