<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use pocketmine\color\Color;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\world\particle\DustParticle;

class HitEntityDelete implements Listener {

    public function Hit(ProjectileHitEvent $event) {
        for ($i = 0; $i <= 0.6; $i += 0.2) {
            $event->getEntity()->getPosition()->getWorld()->addParticle($event->getEntity()->getPosition()->add(0, $i, 0), new DustParticle(new Color(255, 255, 255)));
        }
        if ($event->getEntity()->getWorld()->getFolderName() === 'pvp') {
            return;
        }
        $entity = $event->getEntity();
        $entity->kill();
    }

}
