<?php

declare(strict_types = 0);

namespace bbo51dog\mjolnir;

use bbo51dog\mjolnir\model\Account;
use bbo51dog\mjolnir\service\AccountService;
use bbo51dog\mjolnir\service\BanService;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

	public function onLogin(PlayerLoginEvent $event) {
		AccountService::registerPlayerData($event->getPlayer());
		$player = $event->getPlayer();
		$account = Account::createFromPlayer($player);
		if (BanService::isBanned($account)) {
			$event->setKickMessage(Setting::getInstance()->getKickMessage());
			$event->cancel();
			$reason = "Login from banned account: {$account->getName()}";
			BanService::banName($account->getName(), $reason);
			BanService::banCid($account->getCid(), $reason);
			BanService::banXuid($account->getXuid(), $reason);
		}
	}

	/**
	 * @priority HIGH
	 * @return void
	 */
	public function onQuit(PlayerQuitEvent $event) {
		if ($event->getQuitReason() === Setting::getInstance()->getKickMessage()) {
			$event->setQuitMessage('');
		}
	}
}
