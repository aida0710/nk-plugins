<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\event;

use pocketmine\event\Event;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\player\Player;

class EnchantShopBuyEvent extends Event {

    public function __construct(
        private readonly Player $player,
        private readonly Enchant $enchant,
        private readonly string $enchantName,
        private readonly int $level,
        private readonly int $price,
    ) {
    }

    public function getPlayer() : Player {
        return $this->player;
    }

    public function getEnchant() : Enchant {
        return $this->enchant;
    }

    public function getEnchantName() : string {
        return $this->enchantName;
    }

    public function getLevel() : int {
        return $this->level;
    }

    public function getPrice() : int {
        return $this->price;
    }

}
