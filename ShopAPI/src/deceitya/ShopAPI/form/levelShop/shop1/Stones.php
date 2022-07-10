<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class Stones extends SimpleForm {

    public function __construct(Player $player) {
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
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
