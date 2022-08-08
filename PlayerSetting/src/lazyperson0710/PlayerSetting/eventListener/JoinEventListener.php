<?php

namespace lazyperson0710\PlayerSetting\eventListener;

use lazyperson0710\PlayerSetting\object\PlayerDataPool;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        PlayerDataPool::register($player);
    }

}