<?php

namespace lazyperson0710\Gacha\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;

class ResultForm extends CustomForm {

    public function __construct(string $formMessage, bool $onDrop) {
        if ($onDrop === true) {
            $this->addElements(new Label("§l§c一部または全てのアイテムがInventoryが満タンの為仮想ストレージに入りました§r\n"));
        }
        $this
            ->setTitle("Gacha System")
            ->addElements(
                new Label("ガチャの結果"),
                new Label($formMessage),
            );
    }

}