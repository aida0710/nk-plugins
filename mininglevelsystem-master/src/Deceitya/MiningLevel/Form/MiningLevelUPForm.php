<?php

declare(strict_types=1);

namespace Deceitya\MiningLevel\Form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;

class MiningLevelUPForm extends CustomForm {

	public function __construct(string $msg) {
		$this
			->setTitle("MiningLevel Form")
			->addElements(
				new Label($msg),
			);
	}
}
