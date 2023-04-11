<?php

namespace lazyperson0710\ShopSystem\form\levelShop;

use bbo51dog\bboform\form\SimpleForm;

class ShopSelectForm extends SimpleForm {

	public function __construct() {
		$this
			->setTitle('Shop Form')
			->setText('選択してください')
			->addElements();
	}

}