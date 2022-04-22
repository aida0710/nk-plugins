<?php

namespace deceitya\levelShop\form\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use pocketmine\item\VanillaItems;

class Dyes extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaItems::WHITE_DYE(),
            VanillaItems::LIGHT_GRAY_DYE(),
            VanillaItems::GRAY_DYE(),
            VanillaItems::BLACK_DYE(),
            VanillaItems::BROWN_DYE(),
            VanillaItems::RED_DYE(),
            VanillaItems::ORANGE_DYE(),
            VanillaItems::YELLOW_DYE(),
            VanillaItems::LIME_DYE(),
            VanillaItems::GREEN_DYE(),
            VanillaItems::CYAN_DYE(),
            VanillaItems::LIGHT_BLUE_DYE(),
            VanillaItems::BLUE_DYE(),
            VanillaItems::PURPLE_DYE(),
            VanillaItems::MAGENTA_DYE(),
            VanillaItems::PINK_DYE(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
    }
}
