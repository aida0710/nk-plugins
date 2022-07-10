<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Foods extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::STEAK(),
            VanillaItems::BREAD(),
            VanillaBlocks::CAKE()->asItem(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
