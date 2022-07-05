<?php

namespace deceitya\ShopAPI\form\levelShop\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;

class OtherBlocks7 extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::ITEM_FRAME()->asItem(),
            VanillaBlocks::FLETCHING_TABLE()->asItem(),
            VanillaBlocks::COMPOUND_CREATOR()->asItem(),
            VanillaBlocks::LOOM()->asItem(),
            VanillaBlocks::ELEMENT_CONSTRUCTOR()->asItem(),
            VanillaBlocks::LAB_TABLE()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
