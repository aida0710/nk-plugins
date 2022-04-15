<?php

namespace deceitya\ecoshop\form\shop1;

use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop1Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            '石材系',
            '原木系',
            '鉱石',
            '道具',
            '食べ物',
            'その他アイテム',
            '通貨アイテム'
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop1\\" . $forms[$data];
        $player->sendForm(new $class($player));
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => '石材系'],
                ['text' => '原木系'],
                ['text' => '鉱石'],
                ['text' => '道具'],
                ['text' => '食べ物'],
                ['text' => 'その他アイテム'],
                ['text' => '通貨アイテム'],
            ]
        ];
    }
}
