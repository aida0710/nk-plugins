<?php

namespace deceitya\ShopAPI\form\levelShop\shop6;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\element\FirstBackFormButton;
use deceitya\ShopAPI\form\element\ShopItemFormButton;

class Shop6Form extends SimpleForm {

    public function __construct() {
        $contents = [
            "装飾ブロック類" => "DecorativeBlock",
            "モブヘッド" => "Heads",
            "植物類" => "Vegetation",
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
    //
    //    public function handleResponse(Player $player, $data): void {
    //        if ($data === null) {
    //            return;
    //        }
    //        $forms = [
    //            'DecorativeBlock',
    //            'Heads',
    //            'Vegetation',
    //        ];
    //        $class = "\\deceitya\\ShopAPI\\form\\shop6\\" . $forms[$data];
    //        $player->sendForm(new $class());
    //    }
    //
    //    public function jsonSerialize() {
    //        $shop = LevelShopAPI::getInstance();
    //        return [
    //            'type' => 'form',
    //            'title' => 'LevelShop',
    //            'content' => "§7選択してください",
    //            'buttons' => [
    //                ['text' => 'DecorativeBlock'],
    //                ['text' => 'Heads'],
    //                ['text' => 'Vegetation'],
    //            ]
    //        ];
    //    }
}
