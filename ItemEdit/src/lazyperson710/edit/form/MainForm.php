<?php

declare(strict_types = 0);
namespace lazyperson710\edit\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\edit\form\element\SendFormButton;
use lazyperson710\edit\form\item\MainItemEditForm;
use lazyperson710\edit\form\player\MainPlayerEditForm;
use pocketmine\player\Player;

class MainForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle('Edit Form')
			->setText('選択してください')
			->addElements(
				new SendFormButton(new MainPlayerEditForm($player), 'プレイヤーに関する情報を変更する'),
				new SendFormButton(new MainItemEditForm($player), '所持しているアイテムに関する情報を変更する'),
			);
	}

}
