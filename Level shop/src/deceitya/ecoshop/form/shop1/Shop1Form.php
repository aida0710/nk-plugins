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
            'Stones',
            'Logs',
            'Ores',
            'Tools',
            'Foods',
            'Others',
            'Currency'
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
                ['text' => 'Stones'],
                ['text' => 'Logs'],
                ['text' => 'Ores'],
                ['text' => 'Tools'],
                ['text' => 'Foods'],
                ['text' => 'Others'],
                ['text' => 'Currency'],
            ]
        ];
    }
}
