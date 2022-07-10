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
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
