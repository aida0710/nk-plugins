<?php

namespace Deceitya\SpecificationForm\Form;

use Deceitya\SpecificationForm\Main;
use pocketmine\form\Form;
use pocketmine\player\Player;

class StartForm implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data === 0) {
            $player->sendForm(new SearchForm());
            return;
        }
        $player->sendForm(new ContentForm($data - 1));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'Specification',
            'content' => '見たいコンテンツを選択してください',
            'buttons' => [
                ['text' => "仕様を検索する"],
            ]
        ];
        foreach (Main::getContents("s") as $c) {
            $form['buttons'][] = ['text' => $c['title']];
        }
        return $form;
    }
}
