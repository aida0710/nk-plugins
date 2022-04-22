<?php

namespace deceitya\levelShop\form\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;

class Ores extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
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
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}
