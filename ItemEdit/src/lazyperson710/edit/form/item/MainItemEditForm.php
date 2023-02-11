<?php

declare(strict_types = 0);
namespace lazyperson710\edit\form\item;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\edit\form\element\SendFormButton;
use pocketmine\player\Player;

class MainItemEditForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle('Item Edit')
			->setText('選択してください')
			->addElements(
				new SendFormButton(new ItemEditForm(), 'Itemの表示される情報を操作'),
				new SendFormButton(new CompoundTagForm(), 'CompoundTagを編集する'),
				new SendFormButton(new AddEnchantments(), 'エンチャントを付与する'),
				new SendFormButton(new UnbreakableForm($player), '耐久力の有限/無限を設定'),
			);
	}

}
