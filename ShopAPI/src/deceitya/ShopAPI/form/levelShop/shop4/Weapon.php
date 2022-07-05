<?php

namespace deceitya\ShopAPI\form\levelShop\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;

class Weapon extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::IRON_SWORD(),
            VanillaItems::DIAMOND_SWORD(),
            VanillaItems::BOW(),
            VanillaItems::ARROW(),
            VanillaItems::SNOWBALL(),
            VanillaItems::EGG(),
            513,
            772,
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
