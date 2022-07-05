<?php

namespace deceitya\ShopAPI\form\levelShop\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;

class Vegetation extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::DANDELION()->asItem(),
            VanillaBlocks::POPPY()->asItem(),
            VanillaBlocks::BLUE_ORCHID()->asItem(),
            VanillaBlocks::ALLIUM()->asItem(),
            VanillaBlocks::AZURE_BLUET()->asItem(),
            VanillaBlocks::RED_TULIP()->asItem(),
            VanillaBlocks::ORANGE_TULIP()->asItem(),
            VanillaBlocks::WHITE_TULIP()->asItem(),
            VanillaBlocks::PINK_TULIP()->asItem(),
            VanillaBlocks::OXEYE_DAISY()->asItem(),
            VanillaBlocks::CORNFLOWER()->asItem(),
            VanillaBlocks::LILY_OF_THE_VALLEY()->asItem(),
            VanillaBlocks::LILAC()->asItem(),
            VanillaBlocks::ROSE_BUSH()->asItem(),
            VanillaBlocks::PEONY()->asItem(),
            VanillaBlocks::FERN()->asItem(),
            VanillaBlocks::LARGE_FERN()->asItem(),
            VanillaBlocks::TALL_GRASS()->asItem(),
            VanillaBlocks::DOUBLE_TALLGRASS()->asItem(),
            VanillaBlocks::DEAD_BUSH()->asItem(),
            VanillaBlocks::DEAD_BUSH()->asItem(),
            VanillaBlocks::DEAD_BUSH()->asItem(),
            VanillaBlocks::LILY_PAD()->asItem(),
            VanillaBlocks::VINES()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}

