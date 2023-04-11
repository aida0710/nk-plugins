<?php

declare(strict_types = 0);

namespace lazyperson710\edit\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendFormButton extends Button {

	private Form $form;

	public function __construct(Form $form, string $text, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->form = $form;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, ($this->form));
	}
}
