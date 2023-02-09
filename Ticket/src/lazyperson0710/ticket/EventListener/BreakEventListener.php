<?php

declare(strict_types = 1);
namespace lazyperson0710\ticket\EventListener;

use deceitya\miningtools\event\MiningToolsBreakEvent;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendBroadcastTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use function mt_rand;

class BreakEventListener implements Listener {

	/**
	 * @priority HIGHEST
	 */
	public function onBreak(BlockBreakEvent $event) {
		if ($event->isCancelled()) return;
		$this->blockBreakTicket($event);
	}

	/**
	 * @priority HIGHEST
	 */
	public function onCountEvent(MiningToolsBreakEvent $event) {
		if ($event->isCancelled()) return;
		$this->blockBreakTicket($event);
	}

	public function blockBreakTicket(BlockBreakEvent|MiningToolsBreakEvent $event) {
		$player = $event->getPlayer();
		if ($event->getEventName() === (new BlockBreakEvent($player, $event->getBlock(), $player->getInventory()->getItemInHand()))->getEventName()) {
			$probability = "0.125";
			$random = mt_rand(1, 800);
		} elseif ($event->getEventName() === (new MiningToolsBreakEvent($player, $event->getBlock()))->getEventName()) {
			$probability = "0.0769";
			$random = mt_rand(1, 1300);
		}
		if (empty($random) || empty($probability)) {
			return;
		}
		if ($random === 500) {
			TicketAPI::getInstance()->addTicket($player, 1);
			SendBroadcastTip::Send("Ticketを{$probability}％の確率で{$player->getName()}がゲットしました", "Ticket");
		}
	}
}
