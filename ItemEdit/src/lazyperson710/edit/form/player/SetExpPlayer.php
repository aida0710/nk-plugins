<?php

declare(strict_types = 1);
namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_numeric;

class SetExpPlayer extends CustomForm {

	private Dropdown $players;
	private Input $expLevel;

	public function __construct(Player $player) {
		$names = null;
		foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
			$name = $onlinePlayer->getName();
			$names[] .= $name;
		}
		$this->players = new Dropdown('プレイヤーを選択してください', $names);
		$this->expLevel = new Input('増加させたい経験値量を入力してください', '1');
		$this
			->setTitle('Player Edit')
			->addElements(
				$this->players,
				$this->expLevel,
			);
	}

	public function handleSubmit(Player $player) : void {
		$target = $this->players->getSelectedOption();
		$expLevel = $this->expLevel->getValue();
		if (!Server::getInstance()->getPlayerByPrefix($target)) {
			SendMessage::Send($player, 'プレイヤーが存在しない為、処理を中断しました', 'PlayerEdit', false);
			return;
		}
		$target = Server::getInstance()->getPlayerByPrefix($target);
		if (!is_numeric($expLevel)) {
			SendMessage::Send($player, '経験値量は数値で入力してください', 'PlayerEdit', false);
			return;
		}
		if ($expLevel < 0) {
			SendMessage::Send($player, '経験値レベルは0以上の数値で入力してください', 'PlayerEdit', false);
			return;
		}
		if ($expLevel > 1241258) {
			SendMessage::Send($player, '経験値レベルは1241258以下の数値で入力してください', 'PlayerEdit', false);
			return;
		}
		$target->getXpManager()->setXpLevel($expLevel);
		if ($player->getName() === $target->getName()) {
			SendMessage::Send($player, "経験値レベルを{$expLevel}に設定しました", 'PlayerEdit', true);
		} else {
			SendMessage::Send($player, "経験値レベルを{$expLevel}に設定しました", 'PlayerEdit', true);
			SendMessage::Send($target, "経験値レベルを{$player->getName()}が{$expLevel}に設定しました", 'PlayerEdit', true);
		}
	}

}
