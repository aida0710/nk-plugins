<?php

namespace deceitya\ShopAPI\form\levelShop\shop5;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;

class NetherStones extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            -273,
            -234,
            -225,
            -226,
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
