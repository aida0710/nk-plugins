<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Others extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::WRITABLE_BOOK(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
