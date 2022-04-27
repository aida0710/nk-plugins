<?php

namespace lazyperson710\itemEdit\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendFormButton extends Button {

    private Form $form;

    /**
     * @param Form $form
     * @param string $text
     * @param ButtonImage|null $image
     */
    public function __construct(Form $form, string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->form = $form;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm($this->form);
    }
}