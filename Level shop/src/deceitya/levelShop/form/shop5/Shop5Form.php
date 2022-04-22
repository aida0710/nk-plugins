<?php

namespace deceitya\levelShop\form\shop5;

use deceitya\levelShop\database\LevelShopAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;

class Shop5Form implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $forms = [
            'OtherBlocks5',
            'NetherStones',
            'Others',
        ];
        $class = "\\deceitya\\levelShop\\form\\shop5\\" . $forms[$data];
        $player->sendForm(new $class());
    }

    public function jsonSerialize() {
        $shop = LevelShopAPI::getInstance();
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => '§7選択してください
LevelShop5ではネザー関係のものを販売しています',
            'buttons' => [
                ['text' => 'OtherBlocks'],
                ['text' => 'NetherStones'],
                ['text' => 'Others'],
            ]
        ];
    }
}
