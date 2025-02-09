<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\miningtools\extensions\CheckPlayerData;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendFormButton extends Button {

    private Form $form;

    public function __construct(Form $form, string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->form = $form;
    }

    public function handleSubmit(Player $player) : void {
        if ((new CheckPlayerData())->checkMining4($player) === false) return;
        SendForm::Send($player, ($this->form));
    }
}
