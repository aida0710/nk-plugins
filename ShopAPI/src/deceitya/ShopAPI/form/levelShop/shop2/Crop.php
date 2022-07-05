<?php

namespace deceitya\ShopAPI\form\levelShop\shop2;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;

class Crop extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::WHEAT(),
            VanillaItems::POTATO(),
            VanillaItems::CARROT(),
            VanillaItems::BEETROOT(),
            VanillaItems::SWEET_BERRIES(),
            VanillaBlocks::BAMBOO()->asItem(),
            VanillaBlocks::SUGARCANE()->asItem(),
            VanillaItems::APPLE(),
            VanillaBlocks::MELON()->asItem(),
            VanillaBlocks::PUMPKIN()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
