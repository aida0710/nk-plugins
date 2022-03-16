<?php

namespace lazyperson0710\EffectItems;

use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class DamageEventListener implements Listener {

    public function onDamage(EntityDamageEvent $event) {
        $player = $event->getEntity();
        if (!$player instanceof Player) {
            return;
        }
        if (!$event->isCancelled() && $event->getEntity() instanceof ItemEntity && ($event->getCause() === EntityDamageEvent::CAUSE_FIRE || $event->getCause() === EntityDamageEvent::CAUSE_LAVA)) {
            /** @var ItemEntity $item */
            $item = $event->getEntity();
            if ($item->getItem()->getId() === 384) {
                $event->cancel();
            }
        }
    }
}