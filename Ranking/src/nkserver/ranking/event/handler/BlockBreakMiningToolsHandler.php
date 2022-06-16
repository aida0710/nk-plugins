<?php

declare(strict_types=1);
namespace nkserver\ranking\event\handler;

use deceitya\miningtools\event\MiningToolsBreakEvent;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\Event;

class BlockBreakMiningToolsHandler implements BaseHandler {

    public static function getTarget(): string {
        return MiningToolsBreakEvent::class;
    }

    public static function handleEvent(Event $ev): void {
        if (!$ev instanceof MiningToolsBreakEvent) return;
        self::onBlockBreak($ev);
    }

    protected static function onBlockBreak(MiningToolsBreakEvent $ev): void {
        $world = $ev->getBlock()->getPosition()->getWorld();
        PlayerDataPool::onBlockBreak($ev->getPlayer(), $ev->getBlock(), $world);
    }
}