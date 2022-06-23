<?php

namespace deceitya\ShopAPI\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\element\FirstBackFormButton;
use deceitya\ShopAPI\form\element\ShopItemFormButton;

class Shop3Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "建材ブロック類" => "BuildingMaterials",
            "その他ブロック" => "OtherBlocks3",
            "原石ブロック類" => "Ores",
            "染料アイテム" => "Dyes",
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
