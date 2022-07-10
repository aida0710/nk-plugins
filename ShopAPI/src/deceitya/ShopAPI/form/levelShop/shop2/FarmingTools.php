<?php

namespace deceitya\ShopAPI\form\levelShop\shop2;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class FarmingTools extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::WATER()->asItem(),
            VanillaBlocks::FARMLAND()->asItem(),
            VanillaItems::DIAMOND_HOE(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
