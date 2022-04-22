<?php

namespace deceitya\levelShop\form\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\item\VanillaItems;

class Tools extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaItems::IRON_PICKAXE(),
            VanillaItems::IRON_SHOVEL(),
            VanillaItems::IRON_AXE(),
            VanillaItems::DIAMOND_PICKAXE(),
            VanillaItems::DIAMOND_SHOVEL(),
            VanillaItems::DIAMOND_AXE(),
            VanillaItems::SHEARS(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}
