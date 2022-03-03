<?php

namespace deceitya\ecoshop\form\shop1;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 原木系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [17, 0, 25, 15],##Oak Log
        [17, 1, 25, 15],##Spruce Log
        [17, 2, 25, 15],##Birch Log
        [17, 3, 25, 15],##Jungle Log
        [162, 0, 25, 15],##Acacia Log
        [162, 1, 25, 15],##Dark Oak Log
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
            'content' => "§7選択してください\nネザー系の原木はshop5にあります",
            'buttons' => [
                ['text' => "樫の原木\n§7購入:25 / 売却:15"],
                ['text' => "トウヒの原木\n§7購入:25 / 売却:15"],
                ['text' => "白樺の原木\n§7購入:25 / 売却:15"],
                ['text' => "ジャングルの原木\n§7購入:25 / 売却:15"],
                ['text' => "アカシアの原木\n§7購入:25 / 売却:15"],
                ['text' => "黒樫の原木\n§7購入:25 / 売却:15"],
            ]
        ];
    }
}
