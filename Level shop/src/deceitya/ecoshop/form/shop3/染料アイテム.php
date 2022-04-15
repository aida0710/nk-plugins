<?php

namespace deceitya\ecoshop\form\shop3;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 染料アイテム implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [351, 15, 5, 0],
        [351, 14, 5, 0],
        [351, 13, 5, 0],
        [351, 12, 5, 0],
        [351, 11, 5, 0],
        [351, 10, 5, 0],
        [351, 9, 5, 0],
        [351, 8, 5, 0],
        [351, 7, 5, 0],
        [351, 6, 5, 0],
        [351, 5, 5, 0],
        [351, 18, 5, 0],
        [351, 3, 5, 0],
        [351, 2, 5, 0],
        [351, 1, 5, 0],
        [351, 0, 5, 0],
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop3Form());
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
                ['text' => "白の染料\n§7購入:5 / 売却:0"],
                ['text' => "オレンジの染料\n§7購入:5 / 売却:0"],
                ['text' => "白赤紫の染料\n§7購入:5 / 売却:0"],
                ['text' => "空色の染料\n§7購入:5 / 売却:0"],
                ['text' => "黄色の染料\n§7購入:5 / 売却:0"],
                ['text' => "黄緑の染料\n§7購入:5 / 売却:0"],
                ['text' => "ピンクの染料\n§7購入:5 / 売却:0"],
                ['text' => "灰色の染料\n§7購入:5 / 売却:0"],
                ['text' => "薄灰色の染料\n§7購入:5 / 売却:0"],
                ['text' => "水色の染料\n§7購入:5 / 売却:0"],
                ['text' => "紫の染料\n§7購入:5 / 売却:0"],
                ['text' => "青の染料\n§7購入:5 / 売却:0"],
                ['text' => "茶色の染料\n§7購入:5 / 売却:0"],
                ['text' => "緑の染料\n§7購入:5 / 売却:0"],
                ['text' => "赤の染料\n§7購入:5 / 売却:0"],
                ['text' => "黒の染料\n§7購入:5 / 売却:0"]
            ]
        ];
    }
}
