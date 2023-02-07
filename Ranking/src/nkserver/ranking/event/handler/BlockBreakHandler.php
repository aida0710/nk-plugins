<?php

declare(strict_types=1);
namespace nkserver\ranking\event\handler;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;

class BlockBreakHandler implements BaseHandler {

	public static function getTarget() : string {
		return BlockBreakEvent::class;
	}

	public static function handleEvent(Event $ev) : void {
		if (!$ev instanceof BlockBreakEvent) return;
		self::onBlockBreak($ev);
	}

	protected static function onBlockBreak(BlockBreakEvent $ev) : void {
		$world = $ev->getBlock()->getPosition()->getWorld();
		PlayerDataPool::onBlockBreak($ev->getPlayer(), $ev->getBlock(), $world);
	}
}
