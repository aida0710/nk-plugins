<?php

namespace lazyperson0710\Gacha\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\Gacha\form\GachaForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class SendGachaFormButton extends Button {

    private string $text;

    /**
     * @param string           $text
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->text = $text;
    }

    public function handleSubmit(Player $player): void {
        SendForm::Send($player, (new GachaForm($player, $this->text)));
    }
}