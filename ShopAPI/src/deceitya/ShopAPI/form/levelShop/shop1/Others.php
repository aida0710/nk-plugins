<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;

class Others extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::WRITABLE_BOOK(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
