<?php

namespace deceitya\ShopAPI\form\levelShop\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\element\FirstBackFormButton;
use deceitya\ShopAPI\form\element\ShopItemFormButton;

class Shop1Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "石材類" => "Stones",
            "原木類" => "Logs",
            "鉱石類" => "Ores",
            "ツール" => "Tools",
            "食料アイテム" => "Foods",
            "その他アイテム" => "Others",
            "通貨アイテム" => "Currency",
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
