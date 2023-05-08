<?php

declare(strict_types = 0);

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\Event\EventListener;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_numeric;

class AddMiningExpPlayer extends CustomForm {

	private Dropdown $players;

	private Input $exp;

	public function __construct() {
		$names = null;
		foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
			$name = $onlinePlayer->getName();
			$names[] .= $name;
		}
		$this->players = new Dropdown('プレイヤーを選択してください', $names);
		$this->exp = new Input('増加させたい経験値量を入力してください', '1');
		$this
			->setTitle('Player Edit')
			->addElements(
				$this->players,
				new Label("設定した項目の数値を入力してください\n必ず1以上で入力してください"),
				$this->exp,
			);
	}

	public function handleSubmit(Player $player) : void {
		$targetName = $this->players->getSelectedOption();
		if (!Server::getInstance()->getPlayerByPrefix($targetName)) {
			SendMessage::Send($player, 'プレイヤーが存在しない為、処理を中断しました', 'PlayerEdit', false);
			return;
		}
		$target = Server::getInstance()->getPlayerByPrefix($targetName);
		$exp = $this->exp->getValue();
		$check = $this->checkValue($exp);
		if ($check !== true) {
			SendMessage::Send($player, $check, 'PlayerEdit', false);
			return;
		}
		$exp = (int) $exp;
		EventListener::getInstance()->LevelCalculation($target, $exp);
		if ($player->getName() === $target->getName()) {
			SendMessage::Send($player, "経験値を{$exp}に設定しました", 'PlayerEdit', true);
		} else {
			SendMessage::Send($player, "経験値を{$exp}に設定しました", 'PlayerEdit', true);
			SendMessage::Send($target, "経験値を{$player->getName()}が{$exp}に設定しました", 'PlayerEdit', true);
		}
	}

	private function checkValue(string $value) : bool|string {
		if (!is_numeric($value)) {
			return '数字以外の文字列を入力しないでください';
		}
		$value = (int) $value;
		if ($value < 0) {
			return '0以上の数値で入力してください';
		}
		if ($value > 5000000000000) {
			return '5兆以下の数値で入力してください';
		}
		return true;
	}

}
