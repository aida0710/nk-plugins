<?php

namespace lazyperson0710\EffectItems\items;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class PlayerTeleportTicket {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        $event->getPlayer()->sendForm();
    }
}
