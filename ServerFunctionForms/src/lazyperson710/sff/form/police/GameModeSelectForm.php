<?php

declare(strict_types = 1);
namespace lazyperson710\sff\form\police;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class GameModeSelectForm extends CustomForm {

	private Dropdown $dropdown;

	public function __construct(Player $player) {
		$gameMode = [
			'Survival',
			'Spectator',
		];
		$this->dropdown = new Dropdown('ゲームモードを選択してください', $gameMode);
		$this
			->setTitle('Police System')
			->addElement($this->dropdown);
	}

	public function handleSubmit(Player $player) : void {
		switch ($this->dropdown->getSelectedOption()) {
			case 'Survival':
				$player->setGamemode(GameMode::SURVIVAL());
				SendMessage::Send($player, 'サバイバルモードに変更しました', 'Police', true);
				return;
			case 'Spectator':
				$player->setGamemode(GameMode::SPECTATOR());
				SendMessage::Send($player, 'スペクテイターモードに変更しました', 'Police', true);
				return;
		}
	}
}
