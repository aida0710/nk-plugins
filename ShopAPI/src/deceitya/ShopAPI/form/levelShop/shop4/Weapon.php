<?php

namespace deceitya\ShopAPI\form\levelShop\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Weapon extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::IRON_SWORD(),
            VanillaItems::DIAMOND_SWORD(),
            VanillaItems::BOW(),
            VanillaItems::ARROW(),
            VanillaItems::SNOWBALL(),
            VanillaItems::EGG(),
            513,
            772,
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
