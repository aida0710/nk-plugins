<?php

namespace deceitya\ecoshop\form\shop3;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 建材ブロック implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [98, 0, 25, 5],##Stone Bricks
        [45, 0, 25, 0],##Bricks
        [155, 0, 25, 5],##Quartz Block
        [20, 0, 25, 0],##Glass
        [35, 0, 25, 0],##Wool
        [168, 0, 25, 0],##Prismarine
        [168, 2, 25, 0],##Prismarine Bricks
        [168, 1, 25, 0],##Dark Prismarine
        [172, 0, 25, 0],##Hardened Clay
        [201, 0, 25, 0],##Purpur Block
        [82, 0, 25, 0],##Clay Block
        [87, 0, 50, 1],##Netherrack
        [121, 0, 50, 3],##End Stone
        [89, 0, 150, 15],##Glowstone
        [169, 0, 150, 0],##Sea Lantern
        [12, 1, 25, 0],##Red Sand
        [179, 0, 30, 0],##Red Sandstone
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
                ['text' => "石レンガ\n§7購入:25 / 売却:5"],
                ['text' => "レンガブロック\n§7購入:25 / 売却:0"],
                ['text' => "クォーツブロック\n§7購入:25 / 売却:5"],
                ['text' => "ガラス\n§7購入:25 / 売却:0"],
                ['text' => "羊毛\n§7購入:25 / 売却:0"],
                ['text' => "海晶ブロック\n§7購入:25 / 売却:0"],
                ['text' => "海晶レンガ\n§7購入:25 / 売却:0"],
                ['text' => "暗海晶ブロック\n§7購入:25 / 売却:0"],
                ['text' => "テラコッタ\n§7購入:25 / 売却:0"],
                ['text' => "プルプァブロック\n§7購入:25 / 売却:0"],
                ['text' => "粘土ブロック\n§7購入:25 / 売却:0"],
                ['text' => "ネザーラック\n§7購入:50 / 売却:1"],
                ['text' => "エンドストーン\n§7購入:50 / 売却:3"],
                ['text' => "グロウストーン\n§7購入:150 / 売却:15"],
                ['text' => "海のランタン\n§7購入:150 / 売却:0"],
                ['text' => "赤砂\n§7購入:25 / 売却:0"],
                ['text' => "赤砂岩\n§7購入:30 / 売却:0"],
            ]
        ];
    }
}
