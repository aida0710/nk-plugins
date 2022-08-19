<?php

namespace deceitya\ShopAPI\form\levelShop\shop5;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class OtherItems extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::FLINT(),
            -228,
            -229,
            -231,
            -287,
            -223,
            -224,
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
