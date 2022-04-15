<?php

namespace deceitya\ecoshop\form\shop6;

use deceitya\ecoshop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop6Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            '装飾ブロック',
            '頭ブロック',
            '植物関係',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop6\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => '装飾ブロック'],
                ['text' => '頭ブロック'],
                ['text' => '植物関係'],
            ]
        ];
    }
}
