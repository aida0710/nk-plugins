<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class QuitEventListener implements Listener {

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        //todo データの保存
    }

}