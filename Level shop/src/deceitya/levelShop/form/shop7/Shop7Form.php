<?php

namespace deceitya\levelShop\form\shop7;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\form\element\FirstBackFormButton;
use deceitya\levelShop\form\element\ShopItemFormButton;

class Shop7Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "その他ブロック" => "OtherBlocks7",
            "レッドストーン類" => "RedStone",
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
