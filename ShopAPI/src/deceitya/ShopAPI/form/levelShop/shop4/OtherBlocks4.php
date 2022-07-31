<?php

namespace deceitya\ShopAPI\form\levelShop\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class OtherBlocks4 extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::GRASS()->asItem(),
            VanillaBlocks::PODZOL()->asItem(),
            VanillaBlocks::MYCELIUM()->asItem(),
            VanillaBlocks::MOSSY_COBBLESTONE()->asItem(),
            VanillaBlocks::SMOOTH_STONE()->asItem(),
            VanillaBlocks::SMOOTH_QUARTZ()->asItem(),
            VanillaBlocks::SMOOTH_SANDSTONE()->asItem(),
            VanillaBlocks::SMOOTH_RED_SANDSTONE()->asItem(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
