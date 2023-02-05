<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\listener\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use ymggacha\src\yomogi_server\ymggacha\gacha\GachaRollingPlayersMap;

class UnregisterRollingGachaListener implements Listener {

    public function onQuit(PlayerQuitEvent $ev): void {
        GachaRollingPlayersMap::getInstance()->unregister($ev->getPlayer());
    }
}
