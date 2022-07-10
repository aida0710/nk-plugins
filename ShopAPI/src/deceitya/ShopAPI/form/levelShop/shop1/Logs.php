<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class Logs extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::OAK_LOG()->asItem(),
            VanillaBlocks::SPRUCE_LOG()->asItem(),
            VanillaBlocks::BIRCH_LOG()->asItem(),
            VanillaBlocks::JUNGLE_LOG()->asItem(),
            VanillaBlocks::ACACIA_LOG()->asItem(),
            VanillaBlocks::DARK_OAK_LOG()->asItem(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
