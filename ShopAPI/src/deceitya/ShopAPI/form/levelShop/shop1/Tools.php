<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;

class Tools extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::IRON_PICKAXE(),
            VanillaItems::IRON_SHOVEL(),
            VanillaItems::IRON_AXE(),
            VanillaItems::DIAMOND_PICKAXE(),
            VanillaItems::DIAMOND_SHOVEL(),
            VanillaItems::DIAMOND_AXE(),
            VanillaItems::SHEARS(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
