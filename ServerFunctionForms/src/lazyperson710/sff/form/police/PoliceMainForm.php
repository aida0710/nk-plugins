<?php

declare(strict_types = 0);
namespace lazyperson710\sff\form\police;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\SendFormButton;
use pocketmine\player\Player;

class PoliceMainForm extends SimpleForm {

	public function __construct(Player $player, ?string $error = null) {
		$this
			->setTitle('Police System')
			->setText("使用したい機能を選択してください{$error}")
			->addElements(
				new SendFormButton(new GameModeSelectForm($player), 'ゲームモードを変更', null),
				new SendFormButton(new PlayerTeleportSelectForm($player), 'プレイヤーにTeleport', null),
				new SendFormButton(new PlayerKickSelectForm($player), 'プレイヤーにkick', null),
				new SendFormButton(new PlayerBanSelectForm($player), 'プレイヤーをBan', null),
			);
	}
}
