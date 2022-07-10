<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use pocketmine\player\Player;

class ShopItemFormButton extends Button {

    private string $class;

    /**
     * @param string $text
     * @param string $class
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, string $class, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->class = $class;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new $this->class($player));
    }
}