<?php

declare(strict_types=1);
namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_null;

class ReplaceTicketForm extends CustomForm {

	private Dropdown $playerList;
	private Label $label;

	public function __construct(Player $player) {
		$names = null;
		foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
			$name = $playerName->getName();
			$names[] .= $name;
		}
		if (is_null($names)) {
			$names[] .= "表示可能なプレイヤーが存在しません";
		}
		$this->playerList = new Dropdown("情報を操作したいプレイヤーを選択してください", $names);
		$this->label = new Label("古いTicketを変換してもよろしいでしょうか");
		$this
			->setTitle("Ticket");
		if (Server::getInstance()->isOp($player->getName())) {
			$this->addElement($this->playerList);
		} else {
			$this->addElement($this->label);
		}
	}

	public function handleSubmit(Player $player) : void {
		if (Server::getInstance()->isOp($player->getName())) {
			$playerName = $this->playerList->getSelectedOption();
			if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
				SendMessage::Send($player, "プレイヤーが存在しない為、正常にformを送信できませんでした", "§bTicket", false);
				return;
			}
			$playerInstance = Server::getInstance()->getPlayerByPrefix($playerName);
		} else {
			$playerInstance = $player;
		}
		$count = TicketAPI::getInstance()->replaceInventoryTicket($playerInstance);
		$count += TicketAPI::getInstance()->replaceStackStorageTicket($playerInstance);
		if ($count >= 1) {
			SendMessage::Send($playerInstance, "チケットの変換処理を実行し、{$count}枚のチケットを取得しました", "§bTicket", true);
			if (!isset($playerName)) return;
			if ($playerName != $player->getName()) {
				SendMessage::Send($player, "チケットの変換処理を実行し、{$count}枚のチケットを取得しました", "§bTicket", true);
			}
		} else {
			SendMessage::Send($playerInstance, "変換するアイテムが存在しませんでした", "§bTicket", false);
			if (!isset($playerName)) return;
			if ($playerName != $player->getName())
				SendMessage::Send($player, "変換するアイテムが存在しませんでした", "§bTicket", false);
		}
	}
}
