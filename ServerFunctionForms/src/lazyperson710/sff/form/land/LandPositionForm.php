<?php

declare(strict_types = 0);
namespace lazyperson710\sff\form\land;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\CommandDispatchButton;
use lazyperson710\sff\element\SendFormButton;
use pocketmine\player\Player;

class LandPositionForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle('Land Command')
			->addElements(
				new CommandDispatchButton('1つ目土地範囲地点を決める', 's', null),
				new CommandDispatchButton('2つ目の土地範囲地点を決める', 'e', null),
				new CommandDispatchButton('土地を購入する', 'land buy', null),
				new SendFormButton(new SellForm($player), '土地を売却する', null),
			);
	}
}
