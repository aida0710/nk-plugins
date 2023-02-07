<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SecondBackFormButton extends Button {

	private Form $shopClass;

	public function __construct(string $text, form $shopClass, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->shopClass = $shopClass;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, $this->shopClass);
		SoundPacket::Send($player, 'mob.shulker.close');
	}
}
