<?php

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class StopHunger implements Listener {

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onHunger(PlayerExhaustEvent $event) {
        $WorldName = $event->getPlayer()->getWorld()->getFolderName();
        if (in_array($WorldName, WorldCategory::PublicWorld) || in_array($WorldName, WorldCategory::PublicEventWorld)) {
            $event->cancel();
        }
    }
}