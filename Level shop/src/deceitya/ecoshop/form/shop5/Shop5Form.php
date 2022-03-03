<?php

namespace deceitya\ecoshop\form\shop5;

use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop5Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            '土や光源など',
            'ブラックストーン系',
            'その他アイテム',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop5\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => '§7選択してください
LevelShop5ではネザー関係のものを販売しています',
            'buttons' => [
                ['text' => '土や光源など'],
                ['text' => 'ブラックストーン系'],
                ['text' => 'その他アイテム'],
            ]
        ];
    }
}
