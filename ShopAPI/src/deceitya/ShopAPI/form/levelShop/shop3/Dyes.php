<?php

namespace deceitya\ShopAPI\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;

class Dyes extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::WHITE_DYE(),
            VanillaItems::LIGHT_GRAY_DYE(),
            VanillaItems::GRAY_DYE(),
            VanillaItems::BLACK_DYE(),
            VanillaItems::BROWN_DYE(),
            VanillaItems::RED_DYE(),
            VanillaItems::ORANGE_DYE(),
            VanillaItems::YELLOW_DYE(),
            VanillaItems::LIME_DYE(),
            VanillaItems::GREEN_DYE(),
            VanillaItems::CYAN_DYE(),
            VanillaItems::LIGHT_BLUE_DYE(),
            VanillaItems::BLUE_DYE(),
            VanillaItems::PURPLE_DYE(),
            VanillaItems::MAGENTA_DYE(),
            VanillaItems::PINK_DYE(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
