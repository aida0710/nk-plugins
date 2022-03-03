<?php

namespace Deceitya\MyWarp\Form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class MainForm implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $next = [MakingForm::class, ListForm::class, RemoveForm::class, DetailForm::class];
        $player->sendForm(new $next[$data]($player));
    }

    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'MyWarp',
            'content' => '選択してください',
            'buttons' => [
                [
                    'text' => 'ワープ地点作成'
                ],
                [
                    'text' => 'ワープ地点一覧'
                ],
                [
                    'text' => 'ワープ地点削除'
                ],
                [
                    'text' => 'ワープ地点詳細'
                ]
            ]
        ];
    }
}
