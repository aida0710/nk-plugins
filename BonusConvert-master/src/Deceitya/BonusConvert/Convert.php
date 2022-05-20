<?php

namespace Deceitya\BonusConvert;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class Convert {

    /** @var int */
    private $needCount;
    /** @var Item[] */
    private $items = [];

    public function __construct(int $needCount) {
        $this->needCount = $needCount;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function getNeedCount(): int {
        return $this->needCount;
    }

    public function playerCanConvert(Player $player): bool {
        return $player->getInventory()->contains(ItemFactory::getInstance()->get(-195, 0, $this->needCount));
    }

    public function convert(Player $player, int $index) {
        $player->getInventory()->removeItem(ItemFactory::getInstance()->get(-195, 0, $this->needCount));
        $player->getInventory()->addItem($this->items[$index]);
    }

    public function addItem(Item $item) {
        $this->items[] = $item;
    }
}
