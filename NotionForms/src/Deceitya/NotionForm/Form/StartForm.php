<?php

namespace Deceitya\NotionForm\Form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class StartForm implements Form {

    private array $file;

    public function __construct(array $file) {
        $this->file = $file;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data === 0) {
            $player->sendForm(new SearchForm($this->file));
            return;
        }
        $player->sendForm(new ContentForm($this->file, $data - 1,));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'Notion Form',
            'content' => '見たいコンテンツを選択してください',
            'buttons' => [
                ['text' => "仕様を検索する"],
            ]
        ];
        foreach ($this->file as $c) {
            $form['buttons'][] = ['text' => $c['title']];
        }
        return $form;
    }
}
