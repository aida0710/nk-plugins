<?php

namespace Deceitya\NotionForm\Form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class SearchContentForm implements Form {

    private array $file;
    private $index;
    private $heading;
    private $searchdefault;

    public function __construct(array $file, int $index, array $heading = [], array $searchdefault = []) {
        $this->file = $file;
        $this->index = $index;
        $this->heading = $heading;
        $this->searchdefault = $searchdefault;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if ($data === 0) {
            $player->sendForm(new SearchResultForm($this->heading, $this->searchdefault));
            return;
        }
        if ($data === 1) {
            $player->sendForm(new StartForm($this->file));
        }
    }

    public function jsonSerialize() {
        $content = $this->file[$this->index];
        return [
            'type' => 'form',
            'title' => $content['title'],
            'content' => $content['text'],
            'buttons' => [['text' => '検索結果へ戻る'], ['text' => '戻る']]
        ];
    }
}
