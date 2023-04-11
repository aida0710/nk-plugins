<?php

declare(strict_types = 0);

namespace bbo51dog\announce\form\element;

use bbo51dog\announce\form\AnnounceForm;
use bbo51dog\bboform\element\Button;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class OpenAnnounceButton extends Button {

	private int $announceId;

	public function __construct(string $text, int $announceId) {
		parent::__construct($text);
		$this->announceId = $announceId;
	}

	public function handleSubmit(Player $player) : void {
		SendForm::Send($player, (new AnnounceForm($this->announceId)));
	}
}
