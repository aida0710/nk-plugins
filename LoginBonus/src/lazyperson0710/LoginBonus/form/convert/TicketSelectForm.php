<?php

declare(strict_types = 1);
namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\form\element\SelectLoginBonusTicketButton;

class TicketSelectForm extends SimpleForm {

	public function __construct() {
		$this
			->setTitle("Login Bonus")
			->setText("取得したいアイテムを選択してください");
		for ($i = 1; $i < 16; $i++) {
			$output = $i + (($i * 1.3) * $i);
			$this->addElement(new SelectLoginBonusTicketButton("Ticket x" . (int) $output . " / Cost : " . $i, $i, (int) $output));
		}
	}

}
