<?php

namespace deceitya\ecoshop\form\shop5;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class その他アイテム implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [-228, 0, 50, 0],##Crimson Fungus
        [-229, 0, 50, 0],##Warped Fungus
        [-231, 0, 50, 0],##Weeping Vines
        [-287, 0, 50, 0],##Twisting Vines
        [-223, 0, 50, 0],##Crimson Roots
        [-224, 0, 50, 0],##Warped Roots
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop5Form());
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
                ['text' => "クリムゾンのきのこ\n§7購入:50 / 売却:0"],
                ['text' => "ゆがんだきのこ\n§7購入:50 / 売却:0"],
                ['text' => "ウィーピングつた\n§7購入:50 / 売却:0"],
                ['text' => "ねじれたつた\n§7購入:50 / 売却:0"],
                ['text' => "根（クリムゾン）\n§7購入:50 / 売却:0"],
                ['text' => "ゆがんだ根\n§7購入:50 / 売却:0"],
            ]
        ];
    }
}
