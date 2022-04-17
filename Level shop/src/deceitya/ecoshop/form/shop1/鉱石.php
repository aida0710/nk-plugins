<?php

namespace deceitya\ecoshop\form\shop1;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\element\SellBuyItemFormButton;
use pocketmine\item\VanillaItems;

class 鉱石 extends SimpleForm {

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
    /*// [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [263, 0, 75, 15],##Coal
        [15, 0, 750, 150],##Iron Ore
        [14, 0, 750, 150],##Gold Ore
        [265, 0, 900, 180],##Iron Ingot
        [266, 0, 900, 180],##Gold Ingot
        [351, 4, 750, 80],##Lapis Lazuli
        [331, 0, 75, 15],##Redstone
        [264, 0, 4500, 800],##Diamond
        [388, 0, 15000, 3000],##Emerald
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
            'content' => '§7選択してください
原石のまま売却したい場合はshop2をご覧ください',
            'buttons' => [
                ['text' => "石炭\n§7購入:75 / 売却:15"],
                ['text' => "鉄鉱石\n§7購入:750 / 売却:150"],
                ['text' => "金鉱石\n§7購入:750 / 売却:150"],
                ['text' => "鉄インゴット\n§7購入:900 / 売却:180"],
                ['text' => "金インゴット\n§7購入:900 / 売却:180"],
                ['text' => "ラピスラズリ\n§7購入:750 / 売却:80"],
                ['text' => "レッドストーン\n§7購入:75 / 売却:15"],
                ['text' => "ダイヤモンド\n§7購入:4500 / 売却:800"],
                ['text' => "エメラルド\n§7購入:15000 / 売却:3000"],
            ]
        ];
    }*/
}
