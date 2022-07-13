<?php

declare(strict_types = 1);
namespace nkserver\ranking\event\handler;

use nkserver\ranking\object\PlayerDataPool;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoinHandler implements BaseHandler {

    public static function getTarget(): string {
        return PlayerJoinEvent::class;
    }

    public static function handleEvent(Event $ev): void {
        if (!$ev instanceof PlayerJoinEvent) return;
        self::onJoinPlayer($ev);
    }

    protected static function onJoinPlayer(PlayerJoinEvent $ev): void {
        PlayerDataPool::register($ev->getPlayer());
    }
}