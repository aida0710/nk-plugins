<?php

namespace lazyperson0710\LoginBonus\dataBase;

use pocketmine\item\Item;

class LoginBonusItem {

    private Item $item;
    private int $quantity;
    private int $cost;

    public function __construct(Item $item, int $quantity, int $cost) {
        $this->item = $item;
        $this->quantity = $quantity;
        $this->cost = $cost;
    }

    public function getItem(): LoginBonusItem {
        foreach (ItemRegister::getInstance()->getItems() as $item) {
        }
    }

}