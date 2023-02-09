<?php

declare(strict_types = 1);
namespace lazyperson710\sff\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\donation\DonationAcceptanceForm;
use pocketmine\player\Player;

class DonationsButton extends Button {

	private int $num;
	private string $donor;

	public function __construct(string $text, int $num, ?string $donor = "null", ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->num = $num;
		$this->donor = $donor;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, (new DonationAcceptanceForm($player, $this->num, $this->donor)));
	}
}
