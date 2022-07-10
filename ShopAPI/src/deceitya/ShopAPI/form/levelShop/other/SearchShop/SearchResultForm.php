<?php

namespace deceitya\ShopAPI\form\levelShop\other\SearchShop;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\player\Player;

class SearchResultForm extends SimpleForm {

    public function __construct(Player $player, array $items) {
        (new Calculation())->sendButton($player, "search", $items, $this);
    }

}