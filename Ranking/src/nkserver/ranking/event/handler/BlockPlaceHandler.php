<?php

declare(strict_types = 0);
namespace nkserver\ranking\event\handler;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Event;

class BlockPlaceHandler implements BaseHandler {

	public static function getTarget() : string {
		return BlockPlaceEvent::class;
	}

	public static function handleEvent(Event $ev) : void {
		if (!$ev instanceof BlockPlaceEvent) return;
		self::onBlockPlace($ev);
	}

	protected static function onBlockPlace(BlockPlaceEvent $ev) : void {
		$world = $ev->getBlock()->getPosition()->getWorld();
		PlayerDataPool::onBlockPlace($ev->getPlayer(), $ev->getBlock(), $world);
	}
}
