<?php

namespace lazyperson710\sff\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\DonationAcceptanceForm;
use pocketmine\player\Player;

class DonationsButton extends Button {

    private int $num;

    public function __construct(string $text, int $num, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->num = $num;
    }

    public function handleSubmit(Player $player): void {
        SendForm::Send($player, (new DonationAcceptanceForm($player, $this->num)));
    }
}