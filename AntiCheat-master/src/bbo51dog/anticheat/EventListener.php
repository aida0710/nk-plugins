<?php

declare(strict_types=1);
namespace bbo51dog\anticheat;

use bbo51dog\anticheat\model\PlayerDataFactory;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class EventListener implements Listener {

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		if (PlayerDataFactory::getInstance()->existsPlayerData($player)) {
			PlayerDataFactory::getInstance()->getPlayerData($player)->rejoin($player);
		}
	}

	/**
	 * @priority LOWEST
	 */
	public function onBraek(BlockBreakEvent $event) : void {
		$player = $event->getPlayer();
		if (!$player->isOnline() || $player->isCreative() || Server::getInstance()->isOp($player->getName())) {
			return;
		}
		if (!PlayerDataFactory::getInstance()->existsPlayerData($player)) {
			PlayerDataFactory::getInstance()->createPlayerData($player);
		}
		PlayerDataFactory::getInstance()->getPlayerData($player)->onBreakEvent();
	}

}
