<?php

namespace lazyperson710\core\listener;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class DamageListener implements Listener {

    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if (!$entity instanceof Player) {
            return;
        }
        switch ($event->getCause()) {
            case EntityDamageEvent::CAUSE_FALL:
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                $event->cancel();
                break;
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
            case EntityDamageEvent::CAUSE_PROJECTILE:
                if ($entity->getWorld()->getFolderName() !== "pvp") {
                    $event->cancel();
                }
                break;
        }
    }
}