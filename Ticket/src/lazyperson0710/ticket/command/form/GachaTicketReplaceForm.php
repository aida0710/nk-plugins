<?php

declare(strict_types=1);
namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;

class GachaTicketReplaceForm extends CustomForm {

	private Label $label;

	public function __construct(Player $player) {
		$this->label = new Label("はずれ石をTicketに変換してもよろしいでしょうか");
		$this
			->setTitle("Ticket");
		$this->addElement($this->label);
	}

	public function handleSubmit(Player $player) : void {
		$count = TicketAPI::getInstance()->InventoryConfirmationTicket($player);
		if ($count >= 1) {
			TicketAPI::getInstance()->addTicket($player, $count);
			SendMessage::Send($player, "チケットの変換処理を実行し、{$count}枚のチケットを取得しました", "Ticket", true);
		} else {
			SendMessage::Send($player, "変換するアイテムが存在しませんでした", "Ticket", true);
		}
	}
}
