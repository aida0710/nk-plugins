<?php

namespace deceitya\ecoshop\form\shop5;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ブラックストーン系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [-273, 0, 50, 0],##Blackstone
        [-234, 0, 50, 0],##Basalt
        [-225, 0, 50, 0],##Crimson Stem
        [-226, 0, 50, 0],##Warped Stem
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop5Form());
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => "ブラックストーン\n§7購入:50 / 売却:0"],
                ['text' => "玄武岩\n§7購入:50 / 売却:0"],
                ['text' => "クリムゾンの幹\n§7購入:50 / 売却:0"],
                ['text' => "ゆがんだ幹\n§7購入:50 / 売却:0"],
            ]
        ];
    }
}
