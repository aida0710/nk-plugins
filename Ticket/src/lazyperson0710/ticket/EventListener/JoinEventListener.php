<?php

declare(strict_types = 1);
namespace lazyperson0710\ticket\EventListener;

use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEventListener implements Listener {

	/**
	 * @priority HIGHEST
	 */
	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		if (TicketAPI::getInstance()->dataExists($player) !== true) {
			TicketAPI::getInstance()->createData($event->getPlayer());
		}
		if ((TicketAPI::getInstance()->replaceInventoryTicket($player) + TicketAPI::getInstance()->replaceStackStorageTicket($player)) >= 1) {
			SendMessage::Send($player, "チケットの変換処理を実行しました", "Ticket", true);
		}
	}
}
