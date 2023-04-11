<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\levelShop\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use bbo51dog\bboform\form\FormBase;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\form\levelShop\future\LevelConfirmation;
use pocketmine\player\Player;

class SendMenuFormButton extends Button {

	private FormBase $form;
	private int $restrictionLevel;

	public function __construct(string $text, SimpleForm $form, int $restrictionLevel, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->form = $form;
		$this->restrictionLevel = $restrictionLevel;
	}

	public function handleSubmit(Player $player) : void {
		LevelConfirmation::getInstance()->levelConfirmation($player, $this->form, $this->restrictionLevel);
	}
}
