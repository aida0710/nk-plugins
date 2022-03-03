<?php

namespace deceitya\ecoshop\form\shop3;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 原石ブロック implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [16, 0, 50000, 15],##Coal Ore
        [21, 0, 50000, 40],##Lapis Lazuli Ore
        [73, 0, 50000, 15],##Redstone Ore
        [56, 0, 50000, 800],##Diamond Ore
        [153, 0, 50000, 30],##Nether Quartz Ore
        [129, 0, 50000, 3000],##Emerald Ore
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop3Form());
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => "石炭鉱石\n§7購入:50000 / 売却:15"],
                ['text' => "ラピスラズリ鉱石\n§7購入:50000 / 売却:40"],
                ['text' => "レッドストーン鉱石\n§7購入:50000 / 売却:15"],
                ['text' => "ダイヤモンド鉱石\n§7購入:50000 / 売却:800"],
                ['text' => "ネザークォーツ鉱石\n§7購入:50000 / 売却:30"],
                ['text' => "エメラルド鉱石\n§7購入:50000 / 売却:3000"],
            ]
        ];
    }
}
