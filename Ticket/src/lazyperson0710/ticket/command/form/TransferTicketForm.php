<?php

declare(strict_types = 1);
namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_null;
use function is_numeric;

class TransferTicketForm extends CustomForm {

	private Dropdown $playerList;
	private Label $label;
	private Input $int;

	public function __construct(Player $player) {
		$names = null;
		foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
			$name = $playerName->getName();
			if ($player->getName() === $playerName->getName()) continue;
			$names[] .= $name;
		}
		if (is_null($names)) {
			$names[] .= '表示可能なプレイヤーが存在しません';
		}
		$this->playerList = new Dropdown('プレイヤー', $names);
		$this->label = new Label('チケットの枚数と譲渡したいプレイヤーを選択してください');
		$this->int = new Input('譲渡したい枚数を入力してください', 'int');
		$this
			->setTitle('Ticket')
			->addElements(
				$this->label,
				$this->playerList,
				$this->int,
			);
	}

	public function handleSubmit(Player $player) : void {
		$playerName = $this->playerList->getSelectedOption();
		if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
			SendMessage::Send($player, 'プレイヤーが存在しない為、正常にformを送信できませんでした', 'Ticket', false);
			return;
		}
		$playerInstance = Server::getInstance()->getPlayerByPrefix($playerName);
		if ($this->int->getValue() === '') {
			SendMessage::Send($player, '譲渡したいTicketの枚数を入力してください', 'Ticket', false);
			return;
		}
		if (is_numeric($this->int->getValue()) === false) {
			SendMessage::Send($player, '整数のみ入力してください', 'Ticket', false);
			return;
		}
		if (TicketAPI::getInstance()->reduceTicket($player, (int) $this->int->getValue()) === false) {
			SendMessage::Send($player, "{$playerName}のTicketの枚数が足らないかエラーが発生しました", 'Ticket', false);
			return;
		}
		TicketAPI::getInstance()->addTicket($playerInstance, (int) $this->int->getValue());
		SendMessage::Send($player, "{$playerName}さんにTicketを{$this->int->getValue()}枚譲渡しました", 'Ticket', true);
		SendMessage::Send($playerInstance, "{$player->getName()}さんからTicketを{$this->int->getValue()}枚プレゼントされました", 'Ticket', true);
	}
}
