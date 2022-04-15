<?php

namespace deceitya\ecoshop\form\shop2;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 種と農工具系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [295, 0, 250, 3],##Wheat Seeds
        [458, 0, 250, 3],##Beetroot Seeds
        [361, 0, 250, 3],##Pumpkin Seeds
        [362, 0, 250, 3],##Melon Seeds
        [8, 0, 800, 150],##Water
        [60, 0, 50, 0],##Farmland
        [293, 0, 15000, 0],##Diamond Hoe
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop2Form());
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
                ['text' => "種\n§7購入:250 / 売却:3"],
                ['text' => "ビートルートの種\n§7購入:250 / 売却:3"],
                ['text' => "かぼちゃの種\n§7購入:250 / 売却:3"],
                ['text' => "すいかの種\n§7購入:250 / 売却:3"],
                ['text' => "水\n§7購入:800 / 売却:150"],
                ['text' => "農地\n§7購入:50 / 売却:0"],
                ['text' => "ダイヤモンドのクワ\n§7購入:15000 / 売却:0"],
            ]
        ];
    }
}
