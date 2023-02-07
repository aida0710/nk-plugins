<?php

declare(strict_types=1);

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\CommandDispatchButton;
use pocketmine\player\Player;

class EnchantForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Enchant Form")
			->setText("使用したい機能を選択してください")
			->addElements(
				new CommandDispatchButton("エンチャントを付与", "ven", null),
				new CommandDispatchButton("エンチャントを削減", "enreduce", null),
				new CommandDispatchButton("エンチャントを削除", "endelete", null),
			);
	}
}
