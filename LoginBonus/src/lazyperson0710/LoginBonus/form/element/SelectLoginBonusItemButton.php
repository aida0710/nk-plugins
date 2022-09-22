<?php

namespace lazyperson0710\LoginBonus\form\element;

use bbo51dog\bboform\element\Button;
use lazyperson0710\LoginBonus\calculation\checkInventoryItem;
use lazyperson0710\LoginBonus\dataBase\LoginBonusItemInfo;
use lazyperson0710\LoginBonus\form\convert\ItemConvertConfirmationForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class SelectLoginBonusItemButton extends Button {

    private LoginBonusItemInfo $itemInfo;

    public function __construct(string $text, LoginBonusItemInfo $itemInfo) {
        parent::__construct($text);
        $this->itemInfo = $itemInfo;
    }

    public function handleSubmit(Player $player): void {
        if (checkInventoryItem::init($player, $this->itemInfo->getCost())) {
            SendForm::Send($player, (new ItemConvertConfirmationForm($this->itemInfo)));
        } else {
            $player->sendMessage("§cインベントリ内にあるログインボーナス数が足りません");
        }
    }
}