<?php

namespace deceitya\ShopAPI\form\levelShop\shop5;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;

class OtherBlocks5 extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::SOUL_SAND()->asItem(),
            -236,
            -232,
            -233,
            VanillaBlocks::MAGMA()->asItem(),
            -230,
            VanillaBlocks::NETHER_WART_BLOCK()->asItem(),
            -227,
            -289,
            -272,
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
