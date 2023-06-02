<?php

declare(strict_types = 0);

namespace lazyperson0710\LoginBonus\form\element;

use bbo51dog\bboform\element\Button;
use lazyperson0710\LoginBonus\calculation\CheckInventoryCalculation;
use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson0710\LoginBonus\form\convert\ItemConvertConfirmationForm;
use lazyperson0710\LoginBonus\item\LoginBonusItemInfo;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class SelectLoginBonusItemButton extends Button {

    private LoginBonusItemInfo $itemInfo;

    public function __construct(string $text, LoginBonusItemInfo $itemInfo) {
        parent::__construct($text);
        $this->itemInfo = $itemInfo;
    }

    public function handleSubmit(Player $player) : void {
        if (CheckInventoryCalculation::check($player, $this->itemInfo->getCost())) {
            SendForm::Send($player, (new ItemConvertConfirmationForm($this->itemInfo)));
        } else {
            SendForm::Send($player, new BonusForm($player, "\n§cインベントリ内にあるログインボーナス数が足りません"));
            SoundPacket::Send($player, 'note.bass');
        }
    }
}
