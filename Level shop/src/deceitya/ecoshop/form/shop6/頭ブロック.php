<?php

namespace deceitya\ecoshop\form\shop6;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 頭ブロック implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [397, 3, 800000, 0],##Player Head
        [397, 2, 800000, 0],##Zombie Head
        [397, 0, 800000, 0],##Skeleton Skull
        [397, 4, 800000, 0],##Creeper Head
        [397, 1, 800000, 0],##Wither Skeleton Skull
        [397, 5, 800000, 0],##Dragon Head
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
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => "プレイヤーの頭\n§7購入:800000 / 売却:0"],
                ['text' => "ゾンビの頭\n§7購入:800000 / 売却:0"],
                ['text' => "スケルトンの頭\n§7購入:800000 / 売却:0"],
                ['text' => "クリーパーの頭\n§7購入:800000 / 売却:0"],
                ['text' => "ウィザースケルトンの頭\n§7購入:800000 / 売却:0"],
                ['text' => "ドラゴンの頭\n§7購入:800000 / 売却:0"],
            ]
        ];
    }
}

