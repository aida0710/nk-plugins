<?php

namespace deceitya\miningtools\normal;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;

class BuyForm extends CustomForm {

    public function __construct(Player $player, string $mode) {
        $this
            ->setTitle("Mining Tools")
            ->addElements(
                new Label("")
            );
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new ConfirmForm($player));
    }

}