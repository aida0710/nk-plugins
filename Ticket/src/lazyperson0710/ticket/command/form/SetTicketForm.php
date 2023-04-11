<?php

declare(strict_types = 0);

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_null;
use function is_numeric;

class SetTicketForm extends CustomForm {

	private Dropdown $playerList;
	private Input $int;

	public function __construct(Player $player) {
		$names = null;
		foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
			$name = $playerName->getName();
			$names[] .= $name;
		}
		if (is_null($names)) {
			$names[] .= '表示可能なプレイヤーが存在しません';
		}
		$this->playerList = new Dropdown('情報を操作したいプレイヤーを選択してください', $names);
		$this->int = new Input('Ticketを設定したい数を入力', 'int');
		$this
			->setTitle('Ticket')
			->addElements(
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
		if ($this->int->getValue() === '') {
			SendMessage::Send($player, '設定値を入力してください', 'Ticket', false);
			return;
		}
		if (is_numeric($this->int->getValue()) === false) {
			SendMessage::Send($player, '整数のみ入力してください', 'Ticket', false);
			return;
		}
		$int = TicketAPI::getInstance()->setTicket(Server::getInstance()->getPlayerByPrefix($playerName), (int) $this->int->getValue());
		SendMessage::Send($player, "{$playerName}のTicketを{$int}枚に設定しました", 'Ticket', true);
	}
}
