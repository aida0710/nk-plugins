<?php

namespace lazyperson0710\EffectItems;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class InteractEventListener implements Listener {

    /**
     * @param PlayerInteractEvent $event
     * @return void
     * @priority LOW
     */
    public function onInteract(PlayerInteractEvent $event): void {
        if ($event->isCancelled()) {
            return;
        }
        //必要であれば記述
    }
}
