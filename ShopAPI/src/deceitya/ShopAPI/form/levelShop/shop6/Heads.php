<?php

namespace deceitya\ShopAPI\form\levelShop\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\item\VanillaItems;

class Heads extends SimpleForm {

    public function __construct() {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::PLAYER_HEAD(),
            VanillaItems::ZOMBIE_HEAD(),
            VanillaItems::SKELETON_SKULL(),
            VanillaItems::CREEPER_HEAD(),
            VanillaItems::WITHER_SKELETON_SKULL(),
            VanillaItems::DRAGON_HEAD(),
        ];
        (new Calculation())->sendButton($shopNumber, $contents, $this);
    }
}

