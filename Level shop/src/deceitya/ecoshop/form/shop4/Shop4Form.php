<?php

namespace deceitya\ecoshop\form\shop4;

use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop4Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'エリトラ',
            'その他ブロック',
            '武器関係',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop4\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'エリトラ'],
                ['text' => 'その他ブロック'],
                ['text' => '武器関係'],
            ]
        ];
    }
}
