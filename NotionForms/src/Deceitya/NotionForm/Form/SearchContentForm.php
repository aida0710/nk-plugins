<?php

namespace Deceitya\SpecificationForm\Form;

use Deceitya\SpecificationForm\Main;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SearchContentForm implements Form {

    private $index;
    private $heading;
    private $searchdefault;

    public function __construct(int $index, array $heading = [], array $searchdefault = []) {
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
            $player->sendForm(new StartForm());
            return;
        }
    }

    public function jsonSerialize() {
        $content = Main::getContents()[$this->index];
        return [
            'type' => 'form',
            'title' => $content['title'],
            'content' => $content['text'],
            'buttons' => [['text' => '検索結果へ戻る'], ['text' => '戻る']]
        ];
    }
}
