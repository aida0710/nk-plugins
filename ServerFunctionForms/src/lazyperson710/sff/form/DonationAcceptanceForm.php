<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class DonationAcceptanceForm extends CustomForm {

    public const DonationAmount = 0;

    public function __construct(Player $player, int $num) {
        $amount = self::DonationAmount;
        switch ($num) {
            case 1500:
            case 3000:
            case 5000:
            case 8000:
            case 10000:
            case 15000:
                break;
        }
        $this
            ->setTitle("Donation Form")
            ->addElements(
                new Label("現在寄付額が足りていない為特典を受け取ることができません"),
                new Label("現在の寄付総額 : {$amount}円 | 必要寄付額 : {$num}円"),
            );
    }

    public function handleSubmit(Player $player): void {
        SendForm::Send($player, (new DonationForm()));
    }
}