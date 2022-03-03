<?php

namespace deceitya\ecoshop\form\shop6;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 装飾ブロック implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [720, 0, 80000, 0],##Campfire
        [801, 0, 80000, 0],##Soul Campfire
        [-268, 0, 50, 0],##Soul Torch
        [-208, 0, 50, 0],##Lantern
        [-269, 0, 50, 0],##Soul Lantern
        [-156, 0, 50, 0],##Sea Pickle
        [758, 0, 50, 0],##Chain
        [-206, 0, 150000, 0],##Bell
        [138, 0, 300000, 0],##Beacon
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop6Form());
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
                ['text' => "焚火\n§7購入:80000 / 売却:0"],
                ['text' => "魂の焚火\n§7購入:80000 / 売却:0"],
                ['text' => "魂のたいまつ\n§7購入:50 / 売却:0"],
                ['text' => "ランタン\n§7購入:50 / 売却:0"],
                ['text' => "魂のランタン\n§7購入:50 / 売却:0"],
                ['text' => "なまこ\n§7購入:50 / 売却:0"],
                ['text' => "チェーン\n§7購入:50 / 売却:0"],
                ['text' => "ベル\n§7購入:150000 / 売却:0"],
                ['text' => "§aビーコン\n§7購入:300000 / 売却:0"],
            ]
        ];
    }
}

