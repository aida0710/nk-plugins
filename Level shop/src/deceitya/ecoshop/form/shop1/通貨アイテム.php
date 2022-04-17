<?php

namespace deceitya\ecoshop\form\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use pocketmine\item\VanillaItems;

class 通貨アイテム extends SimpleForm {

    public function __construct() {
        $shop = LevelShopAPI::getInstance();
        $contents = [
            VanillaItems::WRITABLE_BOOK(),
        ];
        $this
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($contents as $content) {
            $this->addElements(new SellBuyItemFormButton("{$content->getName()}\n購入:{$shop->getBuy($content->getId())} / 売却:{$shop->getSell($content->getId())}", $content->getId()));
        }
    }
    /* // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
     const CONTENTS = [
         [399, 0, 1000, 1000],##Nether Star
     ];

     public function handleResponse(Player $player, $data): void {
         if ($data === null) {
             $player->sendForm(new Shop1Form);
             return;
         }
         $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
     }

     public function jsonSerialize() {
         $shop = LevelShopAPI::getInstance();
         return [
             'type' => 'form',
             'title' => 'LevelShop',
             'content' => "§7選択してください",
             'buttons' => [
                 ['text' => "ネザースター\n売買値が一律1000です"],
             ]
         ];
     }*/
}