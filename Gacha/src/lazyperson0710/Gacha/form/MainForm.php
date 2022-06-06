<?php

namespace lazyperson0710\Gacha\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\Gacha\form\element\SendGachaFormButton;
use lazyperson0710\Gacha\Main;

class MainForm extends SimpleForm {

    public function __construct() {
        $this->setTitle("Gacha System");
        foreach (Main::getInstance()->getGachaName() as $key => $name) {
            $this->addElements(new SendGachaFormButton($key, implode($name)));
        }
    }
}