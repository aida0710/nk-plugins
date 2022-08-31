<?php

namespace deceitya\miningtools\element;

use bbo51dog\bboform\element\Button;
use deceitya\miningtools\extensions\CheckPlayerData;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ConfirmationEnablingSettingButton extends Button {

    private Form $form;

    /**
     * @param string $text
     * @param Form   $form
     * @param bool   $enabling
     */
    public function __construct(string $text, Form $form, bool $enabling) {
        parent::__construct($text);
        $this->form = $form;
    }

    public function handleSubmit(Player $player): void {
        if ((new CheckPlayerData())->checkMining4($player) === false) return;
        SendForm::Send($player, ($this->form));
    }
}