<?php

namespace deceitya\ecoshop\form\shop1;

use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class その他アイテム implements Form {

    // [ID, Damage, 1個あたりの購入値段, 1個当たりの売却値段]
    const CONTENTS = [
        [386, 0, 50, 3],##Book & Quill
    ];

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new Shop1Form);
            return;
        }
        $player->sendForm(new SellBuyForm(self::CONTENTS[$data]));
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'LevelShop',
            'content' => "§7選択してください",
            'buttons' => [
                ['text' => "本と羽ペン\n§7購入:50 / 売却:3"]
            ]
        ];
    }
}
