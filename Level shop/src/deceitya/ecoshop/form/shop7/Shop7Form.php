<?php

namespace deceitya\ecoshop\form\shop7;

use deceitya\ecoshop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop7Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'レッドストーン系',
            'その他ブロック',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop7\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'レッドストーン系'],
                ['text' => 'その他ブロック'],
            ]
        ];
    }
}
