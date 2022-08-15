<?php

namespace Deceitya\NotionForm\Form;

use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ContentForm implements Form {

    private $index;
    private array $file;

    public function __construct(array $file, int $index, array $heading = [], array $searchdefault = []) {
        $this->index = $index;
        $this->file = $file;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        SendForm::Send($player, (new StartForm($this->file)));
    }

    public function jsonSerialize() {
        $content = $this->file[$this->index];
        return [
            'type' => 'form',
            'title' => $content['title'],
            'content' => $content['text'],
            'buttons' => [['text' => '戻る']],
        ];
    }
}
