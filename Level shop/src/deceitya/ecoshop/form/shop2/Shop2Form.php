<?php

namespace deceitya\ecoshop\form\shop2;

use deceitya\ecoshop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop2Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'Crop',
            'Seeds',
            'FarmingTools',
        ];
        $class = "\\deceitya\\ecoshop\\form\\shop2\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'Crop'],
                ['text' => 'Seeds'],
                ['text' => 'FarmingTools'],
            ]
        ];
    }
}
