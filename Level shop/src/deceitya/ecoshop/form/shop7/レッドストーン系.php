<?php

namespace deceitya\ecoshop\form\shop7;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class レッドストーン系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [151, 0, 25000, 0],##Daylight Sensor
        [356, 0, 25000, 0],##Redstone Repeater
        [404, 0, 25000, 0],##Redstone Comparator
        [410, 0, 25000, 0],##Hopper
        [46, 0, 25000, 0],##TNT
        [-239, 0, 25000, 0],##Target
        [131, 0, 25000, 0],##Tripwire Hook
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
            'content' => "§7選択してください\n緑の文字のアイテムはただの置物です。機能はないのでご注意ください",
            'buttons' => [
                ['text' => "§a日照センサー\n§7購入:25000 / 売却:0"],
                ['text' => "§aレッドストーン反復装置\n§7購入:25000 / 売却:0"],
                ['text' => "§aレッドストーンコンパレーター\n§7購入:25000 / 売却:0"],
                ['text' => "§aホッパー\n§7購入:25000 / 売却:0"],
                ['text' => "§aTNT\n§7購入:25000 / 売却:0"],
                ['text' => "§aターゲット\n§7購入:25000 / 売却:0"],
                ['text' => "§aトリップワイヤーフック\n§7購入:25000 / 売却:0"],
            ]
        ];
    }
}
