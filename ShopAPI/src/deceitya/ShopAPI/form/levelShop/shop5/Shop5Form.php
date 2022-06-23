<?php

namespace deceitya\ShopAPI\form\levelShop\shop5;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\element\FirstBackFormButton;
use deceitya\ShopAPI\form\element\ShopItemFormButton;

class Shop5Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "ネザーストーン類" => "NetherStones",
            "その他ブロック" => "OtherBlocks5",
            "その他アイテム" => "OtherItems",
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
