<?php

namespace deceitya\miningtools\eventListener;

use deceitya\miningtools\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEventListener implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void {
        Main::$flag[$event->getPlayer()->getName()] = false;
    }
}