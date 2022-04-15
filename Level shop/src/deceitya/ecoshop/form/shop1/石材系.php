<?php

namespace deceitya\ecoshop\form\shop1;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class 石材系 implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [3, 0, 25, 1],##Dirt
        [1, 0, 25, 5],##Stone
        [4, 0, 25, 3],##Cobblestone
        [1, 1, 25, 3],##Granite
        [1, 3, 25, 3],##Diorite
        [1, 5, 25, 3],##Andesite
        [12, 0, 15, 1],##Sand
        [24, 0, 15, 1],##Sandstone
        [13, 0, 15, 1],##Gravel
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop1Form);
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
                ['text' => "土\n§7購入:25 / 売却:1"],
                ['text' => "石\n§7購入:25 / 売却:5"],
                ['text' => "丸石\n§7購入:25 / 売却:3"],
                ['text' => "花崗岩\n§7購入:25 / 売却:3"],
                ['text' => "閃緑岩\n§7購入:25 / 売却:3"],
                ['text' => "安山岩\n§7購入:25 / 売却:3"],
                ['text' => "砂\n§7購入:15 / 売却:1"],
                ['text' => "砂岩\n§7購入:15 / 売却:1"],
                ['text' => "砂利\n§7購入:15 / 売却:1"],
            ]
        ];
    }
}
