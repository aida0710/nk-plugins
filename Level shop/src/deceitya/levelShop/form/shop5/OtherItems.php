<?php

namespace deceitya\levelShop\form\shop5;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\SecondBackFormButton;
use deceitya\levelShop\form\element\SellBuyItemFormButton;

class OtherItems extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            -228,
            -229,
            -231,
            -287,
            -223,
            -224,
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            if (is_int($content)) {
                $item = match ($content) {
                    -228 => "Crimson Fungus",
                    -229 => "Warped Fungus",
                    -231 => "Weeping Vines",
                    -287 => "Twisting Vines",
                    -223 => "Crimson Roots",
                    -224 => "Warped Roots",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($content)} / 売却:{$shop->getSell($content)}", $content, 0));
                continue;
            }
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId() ,$content->getMeta())} / 売却:{$shop->getSell($content->getId() ,$content->getMeta())}", $content->getId(), $content->getMeta()));
        }
        $shopNumber = basename(__DIR__);
        $shopNumber = str_replace("shop", "", $shopNumber);
        $shopNumber = (int)$shopNumber;
        $this->addElements(new SecondBackFormButton("一つ戻る", $shopNumber));
    }
}
