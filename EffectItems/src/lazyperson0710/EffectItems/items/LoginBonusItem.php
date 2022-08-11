<?php

namespace lazyperson0710\EffectItems\items;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\Server;

class LoginBonusItem {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        Server::getInstance()->dispatchCommand($event->getPlayer(), "bonus");
    }
}
