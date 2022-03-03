<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\form\element\FunctionListButton;

abstract class FunctionListForm extends SimpleForm {

    public function addFunction(string $title, string $text, string $description): self {
        $this->addElement(new FunctionListButton($title, $text, $description));
        return $this;
    }
}
