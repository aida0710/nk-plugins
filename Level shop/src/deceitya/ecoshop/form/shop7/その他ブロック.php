<?php

namespace deceitya\ecoshop\form\shop7;

use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class その他ブロック implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [389, 0, 25000, 0],##Item Frame
        [-201, 0, 25000, 0],##Fletching Table
        [238, 0, 25000, 0],##Compound Creator
        [-204, 0, 25000, 0],##Loom
        [238, 8, 25000, 0],##Element Constructor
        [238, 12, 25000, 0],##Lab Table
        [238, 4, 25000, 0],##Material Reducer
        [379, 0, 25000, 0],##Brewing Stand
        [116, 0, 25000, 0],##Enchanting Table
        [-203, 0, 25000, 0],##Barrel
        [25, 0, 25000, 0],##Note Block
        [84, 0, 25000, 0],##Jukebox
        [133, 0, 15000, 0],##Elevator Block
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop7Form());
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください\n緑の文字のアイテムはただの置物です。機能はないのでご注意ください",
            'buttons' => [
                ['text' => "額縁\n§7購入:25000 / 売却:0"],
                ['text' => "§a矢細工台\n§7購入:25000 / 売却:0"],
                ['text' => "§a化合物作成機\n§7購入:25000 / 売却:0"],
                ['text' => "§a織機\n§7購入:25000 / 売却:0"],
                ['text' => "§a元素構成機\n§7購入:25000 / 売却:0"],
                ['text' => "§a実験テーブル\n§7購入:25000 / 売却:0"],
                ['text' => "§a物質還元機\n§7購入:25000 / 売却:0"],
                ['text' => "§a調合台\n§7購入:25000 / 売却:0"],
                ['text' => "§aエンチャントテーブル\n§7購入:25000 / 売却:0"],
                ['text' => "たる\n§7購入:25000 / 売却:0"],
                ['text' => "§a音ブロック\n§7購入:25000 / 売却:0"],
                ['text' => "§aジュークボックス\n§7購入:25000 / 売却:0"],
                ['text' => "エレベーターブロック\n§7購入:15000 / 売却:0"],
            ]
        ];
    }
}
