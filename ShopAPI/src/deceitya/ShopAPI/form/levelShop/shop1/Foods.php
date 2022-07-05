<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;

class Foods extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::STEAK(),
            VanillaItems::BREAD(),
            VanillaBlocks::CAKE()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
