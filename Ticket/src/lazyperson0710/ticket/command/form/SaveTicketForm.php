<?php

declare(strict_types=1);
namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;

class SaveTicketForm extends CustomForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Ticket")
			->addElements(
				new Label("データを保存します"),
			);
	}

	public function handleSubmit(Player $player) : void {
		if (TicketAPI::getInstance()->dataSave() === true) {
			SendMessage::Send($player, "Ticketデータをセーブしました", "Ticket", true);
		} else {
			SendMessage::Send($player, "Ticketデータのセーブに失敗しました", "Ticket", true);
		}
	}
}
