<?php

namespace deceitya\ShopAPI\form\levelShop\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class RedStone extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::DAYLIGHT_SENSOR()->asItem(),
            VanillaBlocks::HOPPER()->asItem(),
            VanillaBlocks::TNT()->asItem(),
            -239,
            VanillaBlocks::TRIPWIRE_HOOK()->asItem(),
            VanillaBlocks::TRAPPED_CHEST()->asItem(),
            VanillaBlocks::REDSTONE_TORCH()->asItem(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
