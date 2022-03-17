<?php

namespace deceitya\ecoshop\form\shop7;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class レッドストーン系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [151, 0, 25000, 0],##Daylight Sensor
        [410, 0, 25000, 0],##Hopper
        [46, 0, 25000, 0],##TNT
        [-239, 0, 25000, 0],##Target
        [131, 0, 25000, 0],##Tripwire Hook
        [146, 0, 2500, 0],##trap chest
        [76, 0, 2500, 0],##Redstone torch
        [356, 0, 2500, 0],##Redstone Repeater
        [404, 0, 2500, 0],##Redstone Comparator
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop7Form());
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください\n緑の文字のアイテムはただの置物です。機能はないのでご注意ください(白文字は機能するブロックです)",
            'buttons' => [
                ['text' => "日照センサー\n§7購入:2500 / 売却:0"],
                ['text' => "ホッパー\n§7購入:2500 / 売却:0"],
                ['text' => "§aTNT\n§7購入:2500 / 売却:0"],
                ['text' => "§aターゲット\n§7購入:25000 / 売却:0"],
                ['text' => "§aトリップワイヤーフック\n§7購入:25000 / 売却:0"],
                ['text' => "トリップチェスト\n§7購入:2500 / 売却:0"],
                ['text' => "レッドストーントーチ\n§7購入:2500 / 売却:0"],
                ['text' => "レッドストーンリピーター\n§7購入:2500 / 売却:0"],
                ['text' => "レッドストーンコンパレーター\n§7購入:2500 / 売却:0"],
            ]
        ];
    }
}
