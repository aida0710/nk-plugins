<?php

namespace deceitya\ShopAPI\form\levelShop\shop2;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Seeds extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::WHEAT_SEEDS(),
            VanillaItems::BEETROOT_SEEDS(),
            VanillaItems::PUMPKIN_SEEDS(),
            VanillaItems::MELON_SEEDS(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
