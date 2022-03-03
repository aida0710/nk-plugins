<?php

namespace deceitya\ecoshop\form\shop1;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 食べ物 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [364, 0, 50, 30],##Steak
        [297, 0, 250, 15],##Bread
        [354, 0, 1500, 50],##Cake
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
                ['text' => "ステーキ\n§7購入:50 / 売却:30"],
                ['text' => "パン\n§7購入:250 / 売却:15"],
                ['text' => "ケーキ\n§7購入:1500 / 売却:50"],
            ]
        ];
    }
}
