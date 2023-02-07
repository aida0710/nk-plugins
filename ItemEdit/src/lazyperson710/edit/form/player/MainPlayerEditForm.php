<?php

declare(strict_types=1);

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\edit\form\element\SendFormButton;
use pocketmine\player\Player;

class MainPlayerEditForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Player Edit")
			->setText("選択してください")
			->addElements(
				new SendFormButton(new SetExpPlayer($player), "バニラ経験値を設定"),
				new SendFormButton(new SetMiningLevelPlayer(), "マイニングレベルを設定"),
				new SendFormButton(new EditSettingPlayer(), "プレイヤーの設定項目を変更"),
				new SendFormButton(new InventoryEditPlayer(), "プレイヤーのインベントリを編集"),
			);
	}

}
