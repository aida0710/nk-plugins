<?php

declare(strict_types = 0);
namespace lazyperson0710\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class ShopItemFormButton extends Button {

	private string $class;

	public function __construct(string $text, string $class, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->class = $class;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, (new $this->class($player)));
	}
}
