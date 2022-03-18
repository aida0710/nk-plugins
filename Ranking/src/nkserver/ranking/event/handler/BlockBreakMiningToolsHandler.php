<?php

declare(strict_types=1);
namespace nkserver\ranking\event\handler;

use deceitya\miningTools\event\CountBlockEvent;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\Event;

class BlockBreakMiningToolsHandler implements BaseHandler {

    public static function getTarget(): string {
        return CountBlockEvent::class;
    }

    public static function handleEvent(Event $ev): void {
        if (!$ev instanceof CountBlockEvent) return;
        self::onBlockBreak($ev);
    }

    protected static function onBlockBreak(CountBlockEvent $ev): void {
        $world = $ev->getBlock()->getPosition()->getWorld();
        PlayerDataPool::onBlockBreak($ev->getPlayer(), $ev->getBlock(), $world);
    }
}