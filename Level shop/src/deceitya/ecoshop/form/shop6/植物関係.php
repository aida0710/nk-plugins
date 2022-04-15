<?php

namespace deceitya\ecoshop\form\shop6;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 植物関係 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [37, 0, 30, 0],
        [38, 0, 30, 0],
        [38, 1, 30, 0],
        [38, 2, 30, 0],
        [38, 3, 30, 0],
        [38, 4, 30, 0],
        [38, 5, 30, 0],
        [38, 6, 30, 0],
        [38, 7, 30, 0],
        [38, 8, 30, 0],
        [175, 0, 30, 0],
        [175, 1, 30, 0],
        [175, 4, 30, 0],
        [175, 5, 30, 0]
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop6Form());
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
                ['text' => "たんぽぽ\n§7購入:30 / 売却:0"],
                ['text' => "ポピー\n§7購入:30 / 売却:0"],
                ['text' => "ヒスイラン\n§7購入:30 / 売却:0"],
                ['text' => "アリウム\n§7購入:30 / 売却:0"],
                ['text' => "ヒナソウ\n§7購入:30 / 売却:0"],
                ['text' => "赤のチューリップ\n§7購入:30 / 売却:0"],
                ['text' => "オレンジのチューリップ\n§7購入:30 / 売却:0"],
                ['text' => "白のチューリップ\n§7購入:30 / 売却:0"],
                ['text' => "ピンクのチューリップ\n§7購入:30 / 売却:0"],
                ['text' => "フランスギク\n§7購入:30 / 売却:0"],
                ['text' => "ヒマワリ\n§7購入:30 / 売却:0"],
                ['text' => "ライラック\n§7購入:30 / 売却:0"],
                ['text' => "バラの低木\n§7購入:30 / 売却:0"],
                ['text' => "ボタン\n§7購入:30 / 売却:0"]
            ]
        ];
    }
}

