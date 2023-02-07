<?php

declare(strict_types=1);
namespace lazyperson0710\EffectItems\form\items;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\SendFormButton;
use pocketmine\player\Player;

class SelectContentForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Item Edit")
			->setText("選択してください")
			->addElements(
				new SendFormButton(new ItemNameChangeForm(), "アイテム名変更"),
				new SendFormButton(new AddMendingEnchantments($player), "修繕エンチャントを付与"),
			);
	}

}
