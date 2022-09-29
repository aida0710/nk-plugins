<?php

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\form\element\SelectLoginBonusItemButton;
use lazyperson0710\LoginBonus\item\ItemRegister;
use lazyperson0710\LoginBonus\item\LoginBonusItemInfo;
use lazyperson710\sff\form\element\SendFormButton;
use pocketmine\Server;

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
                Server::getInstance()->getLogger()->critical("ログインボーナスTicketリスト生成時に不正なobjectが挿入されました。危険と思われるため鯖を停止しました");
                Server::getInstance()->shutdown();
                return;
            }
        }
    }

}