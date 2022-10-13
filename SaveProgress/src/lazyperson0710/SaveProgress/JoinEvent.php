<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if (ProgressDataAPI::getInstance()->dataExists($player) === false) {
            ProgressDataAPI::getInstance()->createData($player);
        }
    }

}