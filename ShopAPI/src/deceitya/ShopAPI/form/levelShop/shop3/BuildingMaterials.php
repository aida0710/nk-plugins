<?php

namespace deceitya\ShopAPI\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;

class BuildingMaterials extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::STONE_BRICKS()->asItem(),
            VanillaBlocks::BRICKS()->asItem(),
            VanillaBlocks::QUARTZ()->asItem(),
            VanillaBlocks::GLASS()->asItem(),
            VanillaBlocks::WOOL()->asItem(),
            VanillaBlocks::PRISMARINE()->asItem(),
            VanillaBlocks::PRISMARINE_BRICKS()->asItem(),
            VanillaBlocks::DARK_PRISMARINE()->asItem(),
            VanillaBlocks::HARDENED_CLAY()->asItem(),
            VanillaBlocks::PURPUR()->asItem(),
            VanillaBlocks::CLAY()->asItem(),
            VanillaBlocks::NETHERRACK()->asItem(),
            VanillaBlocks::END_STONE()->asItem(),
            VanillaBlocks::GLOWSTONE()->asItem(),
            VanillaBlocks::SEA_LANTERN()->asItem(),
            VanillaBlocks::RED_SAND()->asItem(),
            VanillaBlocks::RED_SANDSTONE()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
