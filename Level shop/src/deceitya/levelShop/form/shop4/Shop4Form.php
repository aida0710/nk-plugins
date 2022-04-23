<?php

namespace deceitya\levelShop\form\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\levelShop\database\LevelShopAPI;
use deceitya\levelShop\form\element\FirstBackFormButton;
use deceitya\levelShop\form\element\SellBuyItemFormButton;
use deceitya\levelShop\form\element\ShopItemFormButton;

class Shop4Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "Elytra" => 444,
            "その他ブロック" => "OtherBlocks4",
            "武器類" => "Weapon",
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $key => $value) {
            $class = __NAMESPACE__ . "\\" . $value;
            if (is_int($value)) {
                $shop = LevelShopAPI::getInstance();
                $item = match ($value) {
                    444 => "Elytra",
                    default => "Undefined Error",
                };
                $this->addElements(new SellBuyItemFormButton("{$item}\n購入:{$shop->getBuy($value)} / 売却:{$shop->getSell($value)}", $value, 0));
                continue;
            }
            $this->addElements(new ShopItemFormButton($key, $class));
        }
        $this->addElements(new FirstBackFormButton("ホームに戻る"));
    }
}
