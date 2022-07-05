<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;

class Stones extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::DIRT()->asItem(),
            VanillaBlocks::STONE()->asItem(),
            VanillaBlocks::COBBLESTONE()->asItem(),
            VanillaBlocks::GRANITE()->asItem(),
            VanillaBlocks::DIORITE()->asItem(),
            VanillaBlocks::ANDESITE()->asItem(),
            VanillaBlocks::SAND()->asItem(),
            VanillaBlocks::SANDSTONE()->asItem(),
            VanillaBlocks::GRAVEL()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
