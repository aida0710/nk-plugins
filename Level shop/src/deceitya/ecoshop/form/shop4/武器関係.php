<?php

namespace deceitya\ecoshop\form\shop4;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 武器関係 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [267, 0, 300, 0],##Iron Sword
        [276, 0, 800, 0],##Diamond Sword
        [261, 0, 500, 0],##Bow
        [262, 0, 50, 0],##Arrow
        [332, 0, 15, 1],##Snowball
        [344, 0, 10, 0],##Egg
        [513, 0, 500, 0],##Shield
        [772, 0, 300000, 0],##Spyglass
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop4Form());
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
                ['text' => "鉄の剣\n§7購入:300 / 売却:0"],
                ['text' => "ダイヤモンドの剣\n§7購入:800 / 売却:0"],
                ['text' => "弓\n§7購入:500 / 売却:0"],
                ['text' => "矢\n§7購入:50 / 売却:0"],
                ['text' => "雪だま\n§7購入:15 / 売却:1"],
                ['text' => "卵\n§7購入:10 / 売却:0"],
                ['text' => "盾\n§7購入:500 / 売却:0"],
                ['text' => "双眼鏡\n§7購入:300000 / 売却:0"],
            ]
        ];
    }
}
