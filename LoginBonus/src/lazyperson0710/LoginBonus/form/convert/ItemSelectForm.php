<?php

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\dataBase\ItemRegister;
use lazyperson0710\LoginBonus\dataBase\LoginBonusItemInfo;
use lazyperson0710\LoginBonus\form\element\SelectLoginBonusItemButton;
use lazyperson710\sff\form\element\SendFormButton;

class ItemSelectForm extends SimpleForm {

    public function __construct() {
        $this
            ->setTitle("Login Bonus")
            ->setText("取得したいアイテムを選択してください")
            ->addElement(new SendFormButton(new TicketSelectForm(), "Ticketと交換する"));
        $items = ItemRegister::getInstance()->getItems();
        foreach ($items as $item) {
            $itemInfo = (new LoginBonusItemInfo($item["item"], $item["quantity"], $item["cost"], $item["customName"], $item["lore"], $item["formExplanation"]));
            $this->addElement(new SelectLoginBonusItemButton($itemInfo->getCustomName() . "x" . $itemInfo->getQuantity() . " / Cost : " . $itemInfo->getCost() . "\n" . $itemInfo->getFormExplanation(), $itemInfo));
        }
    }

}