<?php

namespace deceitya\levelShop\form\shop6;

use deceitya\levelShop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop6Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'DecorativeBlock',
            'Heads',
            'Vegetation',
        ];
        $class = "\\deceitya\\levelShop\\form\\shop6\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'DecorativeBlock'],
                ['text' => 'Heads'],
                ['text' => 'Vegetation'],
            ]
        ];
    }
}
