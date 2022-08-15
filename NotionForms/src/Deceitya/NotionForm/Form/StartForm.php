<?php

namespace Deceitya\NotionForm\Form;

use lazyperson710\core\packet\SendForm;
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
            SendForm::Send($player, (new SearchForm($this->file)));
            return;
        }
        SendForm::Send($player, (new ContentForm($this->file, $data - 1,)));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'Notion Form',
            'content' => '見たいコンテンツを選択してください',
            'buttons' => [
                ['text' => "コンテンツを検索"],
            ],
        ];
        foreach ($this->file as $c) {
            $form['buttons'][] = ['text' => $c['title']];
        }
        return $form;
    }
}
