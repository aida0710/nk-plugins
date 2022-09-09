<?php

namespace lazyperson0710\LoginBonus\form;


use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\dataBase\ItemRegister;

class ItemSelectForm extends SimpleForm {

    public function __construct() {
        $this
            ->setTitle("Login Bonus")
            ->setText("取得したいアイテムを選択してください");
        $items = ItemRegister::getInstance()->getItems();
        foreach ($items as $item) {
            $this->addElement(new );
        }

    }

}