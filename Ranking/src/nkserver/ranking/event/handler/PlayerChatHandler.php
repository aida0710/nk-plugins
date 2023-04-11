<?php

declare(strict_types = 0);

namespace nkserver\ranking\event\handler;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerChatEvent;

class PlayerChatHandler implements BaseHandler {

	public static function getTarget() : string {
		return PlayerChatEvent::class;
	}

	public static function handleEvent(Event $ev) : void {
		if (!$ev instanceof PlayerChatEvent) return;
		self::onChatPlayer($ev);
	}

	protected static function onChatPlayer(PlayerChatEvent $ev) : void {
		PlayerDataPool::onChat($ev->getPlayer());
	}
}
