<?php

namespace deceitya\levelShop\form\shop3;

use deceitya\levelShop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop3Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'BuildingMaterials',
            'OtherBlocks3',
            'Ores',
            'Dyes',
        ];
        $class = "\\deceitya\\levelShop\\form\\shop3\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => 'BuildingMaterials'],
                ['text' => 'OtherBlocks'],
                ['text' => 'Ores'],
                ['text' => 'Dyes'],
            ]
        ];
    }
}
