<?php

namespace bbo51dog\announce\form\element;

use bbo51dog\announce\form\AnnounceForm;
use bbo51dog\bboform\element\Button;
use pocketmine\player\Player;

class OpenAnnounceButton extends Button {

    private int $announceId;

    /**
     * @param string $text
     * @param int $announceId
     */
    public function __construct(string $text, int $announceId) {
        parent::__construct($text);
        $this->announceId = $announceId;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new AnnounceForm($this->announceId));
    }
}