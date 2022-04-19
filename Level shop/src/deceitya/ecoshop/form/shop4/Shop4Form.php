<?php

namespace deceitya\ecoshop\form\shop4;

use deceitya\ecoshop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop4Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'Elytra',
            'OtherBlocks',
            'Weapon',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop4\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'Elytra'],
                ['text' => 'OtherBlocks'],
                ['text' => 'Weapon'],
            ]
        ];
    }
}
