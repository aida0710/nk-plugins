<?php

namespace deceitya\ecoshop\form\shop2;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 作物系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [296, 0, 250, 8],##Wheat
        [392, 0, 250, 8],##Potato
        [391, 0, 250, 8],##Carrot
        [457, 0, 250, 8],##Beetroot
        [477, 0, 250, 8],##Sweet Berries
        [-163, 0, 250, 4],##Bamboo
        [338, 0, 250, 4],##Sugarcane
        [260, 0, 250, 8],##Apple
        [103, 0, 500, 6],##Melon Block
        [86, 0, 500, 6],##Pumpkin
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop2Form());
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
                ['text' => "小麦\n§7購入:250 / 売却:8"],
                ['text' => "じゃがいも\n§7購入:250 / 売却:8"],
                ['text' => "人参\n§7購入:250 / 売却:8"],
                ['text' => "ビートルート\n§7購入:250 / 売却:8"],
                ['text' => "スイートベリー\n§7購入:250 / 売却:8"],
                ['text' => "竹\n§7購入:250 / 売却:4"],
                ['text' => "サトウキビ\n§7購入:250 / 売却:4"],
                ['text' => "リンゴ\n§7購入:250 / 売却:8"],
                ['text' => "メロンブロック\n§7購入:500 / 売却:6"],
                ['text' => "かぼちゃブロック\n§7購入:500 / 売却:6"],
            ]
        ];
    }
}
