<?php

namespace ipad54\netherblocks\listener;

use ipad54\netherblocks\player\Player as MyPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

class EventListener implements Listener {

    public function onPlayerCreation(PlayerCreationEvent $event) {
        $event->setPlayerClass(MyPlayer::class);
    }
}