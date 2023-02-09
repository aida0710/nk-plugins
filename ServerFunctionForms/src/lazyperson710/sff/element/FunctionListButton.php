<?php

declare(strict_types = 1);
namespace lazyperson710\sff\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class FunctionListButton extends Button {

	private string $title;
	private string $description;

	public function __construct(string $title, string $text, string $description) {
		parent::__construct($text);
		$this->title = $title;
		$this->description = $description;
	}

	public function handleSubmit(Player $player) : void {
		$form = (new SimpleForm())
			->setTitle($this->title)
			->setText($this->description)
			->addElement(new Button('閉じる'));
		SendForm::Send($player, ($form));
	}
}
