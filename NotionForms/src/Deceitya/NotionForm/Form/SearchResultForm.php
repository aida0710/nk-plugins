<?php

namespace Deceitya\SpecificationForm\Form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class SearchResultForm implements Form {

    public $heading = [];
    public $index = [];
    public $searchdefault = [];

    public function __construct(array $heading, array $searchdefault = []) {
        $this->heading = $heading;
        $this->searchdefault = $searchdefault;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data === 0) {
            $player->sendForm(new SearchForm("", $this->searchdefault));
            return;
        }
        if (!isset($this->index[$data - 1])) {
            $player->sendMessage("[NotionForm][SearchResultForm] error1");
            return;
        }
        $id = $this->index[$data - 1];
        $player->sendForm(new SearchContentForm($id, $this->heading, $this->searchdefault));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'Specification',
            'content' => '見たいコンテンツを選択してください',
            'buttons' => [
                ['text' => "戻る"]
            ]
        ];
        foreach ($this->heading as $key => $heading) {
            $form['buttons'][] = ['text' => $heading];
            $this->index[] = $key;
        }
        return $form;
    }
}
