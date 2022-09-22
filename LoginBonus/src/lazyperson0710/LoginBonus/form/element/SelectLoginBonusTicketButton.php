<?php

namespace lazyperson0710\LoginBonus\form\element;

use bbo51dog\bboform\element\Button;
use lazyperson0710\LoginBonus\calculation\checkInventoryItem;
use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson0710\LoginBonus\form\convert\TicketConvertConfirmationForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class SelectLoginBonusTicketButton extends Button {

    private int $cost;
    private int $quantity;

    public function __construct(string $text, int $cost, int $quantity) {
        parent::__construct($text);
        $this->cost = $cost;
        $this->quantity = $quantity;
    }

    public function handleSubmit(Player $player): void {
        if (checkInventoryItem::init($player, $this->cost)) {
            SendForm::Send($player, (new TicketConvertConfirmationForm($this->cost, $this->quantity)));
        } else {
            SendForm::Send($player, new BonusForm($player, "\n§cインベントリ内にあるログインボーナス数が足りません"));
        }
    }
}