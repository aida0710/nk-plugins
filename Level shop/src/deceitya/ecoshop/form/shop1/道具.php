<?php

namespace deceitya\ecoshop\form\shop1;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 道具 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [257, 0, 150, 0],##Iron Pickaxe
        [256, 0, 150, 0],##Iron Shovel
        [258, 0, 150, 0],##Iron Axe
        [278, 0, 250, 0],##Diamond Pickaxe
        [277, 0, 250, 0],##Diamond Shovel
        [279, 0, 250, 0],##Diamond Axe
        [359, 0, 15000, 0],##Shears
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop1Form);
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
                ['text' => "鉄のつるはし\n§7購入:150 / 売却:0"],
                ['text' => "鉄のシャベル\n§7購入:150 / 売却:0"],
                ['text' => "鉄の斧\n§7購入:150 / 売却:0"],
                ['text' => "ダイヤモンドのつるはし\n§7購入:250 / 売却:0"],
                ['text' => "ダイヤモンドのシャベル\n§7購入:250 / 売却:0"],
                ['text' => "ダイヤモンドの斧\n§7購入:250 / 売却:0"],
                ['text' => "ハサミ\n§7購入:15000 / 売却:0"],
            ]
        ];
    }
}
