<?php

namespace deceitya\ecoshop\form\shop5;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 土や光源など implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [88, 0, 250, 3],##Soul Sand
        [-236, 0, 50, 0],##Soul Soil
        [-232, 0, 50, 0],##Crimson Nylium
        [-233, 0, 50, 0],##Warped Nylium
        [213, 0, 50, 0],##Magma Block
        [-230, 0, 50, 0],##Shroomlight
        [214, 0, 50, 0],##Nether Wart Block
        [-227, 0, 50, 0],##Warped Wart Block
        [-289, 0, 50, 0],##Crying Obsidian
        [-272, 0, 50, 0],##Respawn Anchor
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
                ['text' => "ソウルサンド\n§7購入:250 / 売却:3"],
                ['text' => "魂の土壌\n§7購入:50 / 売却:0"],
                ['text' => "ニリウム（クリムゾン）\n§7購入:50 / 売却:0"],
                ['text' => "ゆがんだニリウム\n§7購入:50 / 売却:0"],
                ['text' => "マグマブロック\n§7購入:50 / 売却:0"],
                ['text' => "キノコライト\n§7購入:50 / 売却:0"],
                ['text' => "ネザーウォートブロック\n§7購入:50 / 売却:0"],
                ['text' => "ゆがんだウォートブロック\n§7購入:50 / 売却:0"],
                ['text' => "泣く黒曜石\n§7購入:50 / 売却:0"],
                ['text' => "リスポーンアンカー\n§7購入:50 / 売却:0"],
            ]
        ];
    }
}
