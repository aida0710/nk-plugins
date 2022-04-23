<?php

namespace deceitya\levelShop\form\shop2;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\form\element\FirstBackFormButton;
use deceitya\levelShop\form\element\ShopItemFormButton;

class Shop2Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "作物類" => "Crop",
            "種子類" => "Seeds",
            "農耕系ツール&アイテム" => "FarmingTools",
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $key => $value) {
            $class = __NAMESPACE__ . "\\" . $value;
            $this->addElements(new ShopItemFormButton($key, $class));
        }
        $this->addElements(new FirstBackFormButton("ホームに戻る"));
    }
}
