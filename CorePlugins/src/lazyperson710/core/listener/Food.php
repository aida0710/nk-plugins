<?php

namespace lazyperson710\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class Food implements Listener {

    /**
     * 特定のワールドにて、空腹の減少を停止するコード
     *
     * @param PlayerExhaustEvent $event
     */
    public function onHunger(PlayerExhaustEvent $event) {
        $WorldName = $event->getPlayer()->getWorld()->getFolderName();
        if ($WorldName === "lobby" || $WorldName === "rule" || $WorldName === "athletic") {
            $event->cancel();
        }
    }
}