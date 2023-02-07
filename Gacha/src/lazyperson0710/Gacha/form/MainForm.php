<?php

declare(strict_types=1);
namespace lazyperson0710\Gacha\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\Gacha\database\GachaItemAPI;
use lazyperson0710\Gacha\form\element\SendGachaFormButton;

class MainForm extends SimpleForm {

	public function __construct() {
		$this->setTitle("Gacha System");
		foreach (GachaItemAPI::Category as $category) {
			$this->addElements(new SendGachaFormButton($category));
		}
	}
}
