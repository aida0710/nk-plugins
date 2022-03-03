<?php

namespace deceitya\ecoshop\form\shop4;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class その他ブロック implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [2, 0, 10, 1],##Grass
        [243, 0, 10, 0],##Podzol
        [110, 0, 10, 0],##Mycelium
        [3, 1, 10, 0],##Dirt
        [48, 0, 10, 0],##Mossy Cobblestone
        [-183, 0, 10, 3],##Smooth Stone
        [-155, 3, 10, 0],##Smooth Quartz Block
        [24, 3, 10, 0],##Smooth Sandstone
        [179, 3, 10, 0],##Smooth Red Sandstone
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
                ['text' => "草ブロック\n§7購入:10 / 売却:1"],
                ['text' => "ポドゾル\n§7購入:10 / 売却:0"],
                ['text' => "菌糸ブロック\n§7購入:10 / 売却:0"],
                ['text' => "荒れた土\n§7購入:10 / 売却:0"],
                ['text' => "苔の生えた丸石\n§7購入:10 / 売却:0"],
                ['text' => "なめらかな石\n§7購入:10 / 売却:3"],
                ['text' => "なめらかなクォーツブロック\n§7購入:10 / 売却:0"],
                ['text' => "なめらかな砂岩\n§7購入:10 / 売却:0"],
                ['text' => "なめらかな赤砂岩\n§7購入:10 / 売却:0"],
            ]
        ];
    }
}
