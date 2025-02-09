<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\Listener;

class DropItemSetDeleteTime implements Listener {

    private const DeleteTime = 20 * 60;

    public function onDropItemSetDeleteTime(ItemSpawnEvent $event) : void {
        if ($event->getEntity() instanceof ItemEntity) {
            $event->getEntity()->setDespawnDelay(self::DeleteTime);
        }
    }

}
