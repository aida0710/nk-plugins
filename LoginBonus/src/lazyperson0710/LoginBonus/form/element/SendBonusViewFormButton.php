<?php

namespace lazyperson0710\LoginBonus\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson0710\LoginBonus\Main;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendBonusViewFormButton extends Button {

    private Form $form;

    /**
     * @param Form             $form
     * @param string           $text
     * @param ButtonImage|null $image
     */
    public function __construct(Form $form, string $text, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->form = $form;
    }

    public function handleSubmit(Player $player): void {
        if (Main::getInstance()->lastBonusDateConfig->get($player->getName()) === false) {
            SendForm::Send($player, new BonusForm($player, "\n§c現在保留中のログインボーナスが存在しない為、受け取ることはできません"));
        }
        SendForm::Send($player, $this->form);
    }
}