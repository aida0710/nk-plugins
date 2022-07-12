<?php

namespace lazyperson0710\Gacha\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\Gacha\form\GachaForm;
use pocketmine\player\Player;

class SendGachaFormButton extends Button {

    private string $text;
    private int $key;

    /**
     * @param string $text
     * @param int $key
     * @param ButtonImage|null $image
     */
    public function __construct(int $key, string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->text = $text;
        $this->key = $key;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new GachaForm($player, $this->text, $this->key));
    }
}