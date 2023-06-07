<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\form\temp;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendNormalSettingFormButton extends Button {

    private Form $form;

    public function __construct(Form $form, string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->form = $form;
    }

    public function handleSubmit(Player $player) : void {
        SendForm::Send($player, $this->form);
    }
}
