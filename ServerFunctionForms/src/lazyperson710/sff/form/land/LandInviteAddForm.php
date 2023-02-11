<?php

declare(strict_types = 0);
namespace lazyperson710\sff\form\land;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SoundPacket;
use lazyperson710\sff\form\LandForm;
use pocketmine\player\Player;
use pocketmine\Server;
use function preg_match;

class LandInviteAddForm extends CustomForm {

	private Input $input1;
	private Input $input2;

	public function __construct() {
		$this->input1 = new Input(
			"他プレイヤーに共有したい土地番号を入力してください\n自分の持っている土地は/land whoseか/land hereから調べることが可能です",
			'例: 1',
		);
		$this->input2 = new Input(
			"土地を共有したいプレイヤーを入力\n必ずプレイヤーidは省略しないで記述してください\nまた、オンラインプレイヤーであることを確認してください",
			'例: lazyperson710'
		);
		$this
			->setTitle('Land Command')
			->addElements(
				$this->input1,
				$this->input2,
			);
	}

	public function handleClosed(Player $player) : void {
		SendForm::Send($player, (new LandForm($player)));
		SoundPacket::Send($player, 'note.bass');
		return;
	}

	public function handleSubmit(Player $player) : void {
		if ($this->input1->getValue() === '') {
			SendMessage::Send($player, '土地番号を入力してください', 'Land', false);
			return;
		} elseif (!preg_match('/^[0-9]+$/', $this->input1->getValue())) {
			SendMessage::Send($player, '土地番号は数字で入力してください', 'Land', false);
			return;
		}
		if ($this->input2->getValue() === '') {
			SendMessage::Send($player, 'プレイヤーidを入力してください', 'Land', false);
			return;
		}
		if (!Server::getInstance()->getPlayerExact($this->input2->getValue())) {
			SendMessage::Send($player, 'オンラインのプレイヤーを入力してください', 'Land', false);
			return;
		}
		Server::getInstance()->dispatchCommand($player, "land invite {$this->input1->getValue()} {$this->input2->getValue()}");
	}

}
