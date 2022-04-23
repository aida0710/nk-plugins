<?php

namespace deceitya\levelShop\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\sff\form\ShopForm;
use pocketmine\player\Player;

class FirstBackFormButton extends Button {

    /**
     * @param string $text
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new ShopForm($player));
    }
}