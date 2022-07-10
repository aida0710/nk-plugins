<?php

namespace deceitya\ShopAPI\form\levelShop\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class DecorativeBlock extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            720,
            801,
            -268,
            VanillaBlocks::LANTERN()->asItem(),
            -269,
            VanillaBlocks::SEA_PICKLE()->asItem(),
            758,
            VanillaBlocks::BELL()->asItem(),
            VanillaBlocks::BEACON()->asItem(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}

