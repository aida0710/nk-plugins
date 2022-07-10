<?php

namespace deceitya\ShopAPI\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class Ores extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaBlocks::COAL_ORE()->asItem(),
            VanillaBlocks::LAPIS_LAZULI_ORE()->asItem(),
            VanillaBlocks::REDSTONE_ORE()->asItem(),
            VanillaBlocks::DIAMOND_ORE()->asItem(),
            VanillaBlocks::NETHER_QUARTZ_ORE()->asItem(),
            VanillaBlocks::EMERALD_ORE()->asItem(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
