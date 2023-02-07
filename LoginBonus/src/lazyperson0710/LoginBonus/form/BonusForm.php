<?php

declare(strict_types=1);

namespace lazyperson0710\LoginBonus\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\form\acceptance\BonusViewForm;
use lazyperson0710\LoginBonus\form\convert\ItemSelectForm;
use lazyperson0710\LoginBonus\form\create\CreateLoginBonusForm;
use lazyperson0710\LoginBonus\form\element\SendBonusViewFormButton;
use lazyperson710\sff\element\SendFormButton;
use pocketmine\player\Player;
use pocketmine\Server;

class BonusForm extends SimpleForm {

	public function __construct(Player $player, ?string $message = null) {
		$this
			->setTitle("Login Bonus")
			->setText("使用したい機能を選択してください" . $message)
			->addElements(
				new SendFormButton(new ItemSelectForm(), "ログインボーナスをアイテムと交換"),
				new SendBonusViewFormButton(new BonusViewForm($player), "保留しているログインボーナスを回収"),
			);
		if (Server::getInstance()->isOp($player->getName())) {
			$this->addElements(new SendFormButton(new CreateLoginBonusForm(), "ログインボーナスを錬金"));
		}
	}
}
