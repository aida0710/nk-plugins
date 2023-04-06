<?php

declare(strict_types = 0);
namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ticket\command\form\element\SendFormButton;
use pocketmine\player\Player;
use pocketmine\Server;

class MainTicketForm extends SimpleForm {

	public function __construct(Player $player) {
		if (Server::getInstance()->isOp($player->getName())) {
			$this
				->setTitle('Ticket')
				->addElements(
					new SendFormButton(new PurchaseTicketForm($player), 'Ticketを購入する'),
					new SendFormButton(new CheckTicketForm($player), 'Ticket枚数を調べる'),
					new SendFormButton(new SetTicketForm($player), 'Ticket枚数を設定する'),
					new SendFormButton(new AddTicketForm($player), 'Ticket枚数を増やす'),
					new SendFormButton(new ReduceTicketForm($player), 'Ticket枚数を減らす'),
					new SendFormButton(new GachaTicketReplaceForm($player), "Ticketを変換する\n[gachaはずれ -> ticket]"),
					new SendFormButton(new ReplaceTicketForm($player), "Ticketを変換する\n[旧 -> 新]"),
					new SendFormButton(new SaveTicketForm($player), 'Ticketデータをセーブする'),
				);
		} else {
			$this
				->setTitle('Ticket')
				->addElements(
					new SendFormButton(new TransferTicketForm($player), 'Ticketを譲渡する'),
					new SendFormButton(new GachaTicketReplaceForm($player), "Ticketを変換する\n[gachaはずれ -> ticket]"),
					new SendFormButton(new ReplaceTicketForm($player), "Ticketを変換する\n[旧 -> 新]"),
				);
		}
	}
}
