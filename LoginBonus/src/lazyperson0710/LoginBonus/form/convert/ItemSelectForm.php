<?php

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\form\element\SelectLoginBonusItemButton;
use lazyperson0710\LoginBonus\item\ItemRegister;
use lazyperson0710\LoginBonus\item\LoginBonusItemInfo;
use lazyperson710\sff\form\element\SendFormButton;

class ItemSelectForm extends SimpleForm {

    public function __construct() {
        $this
            ->setTitle("Login Bonus")
            ->setText("取得したいアイテムを選択してください")
            ->addElement(new SendFormButton(new TicketSelectForm(), "Ticketと交換する"));
        $items = ItemRegister::getInstance()->getItems();
        foreach ($items as $item) {
            if ($item instanceof LoginBonusItemInfo) {
                $this->addElement(new SelectLoginBonusItemButton($item->getCustomName() . "x" . $item->getQuantity() . " / Cost : " . $item->getCost() . "\n" . $item->getFormExplanation(), $item));
            } else {
                //todo サーバー停止させる
                return;
            }
        }
    }

}