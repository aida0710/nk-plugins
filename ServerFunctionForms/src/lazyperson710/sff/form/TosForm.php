<?php

declare(strict_types = 1);
namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\Main;

class TosForm extends SimpleForm {

	public function __construct() {
		$this->setTitle('Terms of Service');
		$this->setText(Main::getTos());
		$this->addElement(new Button('閉じる'));
	}
}
