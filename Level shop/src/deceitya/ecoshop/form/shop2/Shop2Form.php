<?php

namespace deceitya\ecoshop\form\shop2;

use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop2Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            '作物系',
            '種と農工具系',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop2\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => '作物系'],
                ['text' => '種と農工具系'],
            ]
        ];
    }
}
