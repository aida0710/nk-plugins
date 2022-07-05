<?php

namespace deceitya\ShopAPI\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;

class OtherBlocks3 extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::PACKED_ICE()->asItem(),
            VanillaBlocks::OBSIDIAN()->asItem(),
            VanillaBlocks::END_ROD()->asItem(),
            VanillaBlocks::ANVIL()->asItem(),
            VanillaBlocks::SHULKER_BOX()->asItem(),
            VanillaBlocks::SLIME()->asItem(),
            VanillaBlocks::BOOKSHELF()->asItem(),
            VanillaBlocks::COBWEB()->asItem(),
            VanillaBlocks::BLAST_FURNACE()->asItem(),
            VanillaBlocks::SMOKER()->asItem(),
            VanillaBlocks::LECTERN()->asItem(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}
