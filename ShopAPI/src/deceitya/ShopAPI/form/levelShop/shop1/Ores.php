<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Ores extends SimpleForm {

    public function __construct(Player $player) {
        $shopNumber = basename(__DIR__);
        $contents = [
            VanillaItems::COAL(),
            VanillaBlocks::IRON_ORE()->asItem(),
            VanillaBlocks::GOLD_ORE()->asItem(),
            VanillaItems::IRON_INGOT(),
            VanillaItems::GOLD_INGOT(),
            VanillaItems::LAPIS_LAZULI(),
            VanillaItems::REDSTONE_DUST(),
            VanillaItems::DIAMOND(),
            VanillaItems::EMERALD(),
        ];
        (new Calculation())->sendButton($player, $shopNumber, $contents, $this);
    }
}
