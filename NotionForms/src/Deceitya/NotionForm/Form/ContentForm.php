<?php

namespace Deceitya\SpecificationForm\Form;

use Deceitya\SpecificationForm\Main;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ContentForm implements Form {

    private $index;

    public function __construct(int $index, array $heading = [], array $searchdefault = []) {
        $this->index = $index;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $player->sendForm(new StartForm());
    }

    public function jsonSerialize() {
        $content = Main::getContents()[$this->index];
        return [
            'type' => 'form',
            'title' => $content['title'],
            'content' => $content['text'],
            'buttons' => [['text' => '戻る']]
        ];
    }
}
