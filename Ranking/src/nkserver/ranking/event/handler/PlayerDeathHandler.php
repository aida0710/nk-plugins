<?php

declare(strict_types = 1);
namespace nkserver\ranking\event\handler;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerDeathEvent;

class PlayerDeathHandler implements BaseHandler {

	public static function getTarget() : string {
		return PlayerDeathEvent::class;
	}

	public static function handleEvent(Event $ev) : void {
		if (!$ev instanceof PlayerDeathEvent) return;
		self::onDeathPlayer($ev);
	}

	protected static function onDeathPlayer(PlayerDeathEvent $ev) : void {
		PlayerDataPool::onDeath($ev->getPlayer());
	}
}
