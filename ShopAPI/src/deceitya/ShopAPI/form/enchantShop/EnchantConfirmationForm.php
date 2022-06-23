<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;

class EnchantConfirmationForm extends CustomForm {

    public function __construct() {
        switch ($this->getData()) {
            case
            $this
                ->setTitle("Enchant Form")
                ->addElements(
                    new Label("Please select an enchantment"),
                );
        }
    }