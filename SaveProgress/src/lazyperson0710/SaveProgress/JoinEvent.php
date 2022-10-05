<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class JoinEvent implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if (!file_exists(Main::getInstance()->getDataFolder())) {
            mkdir(Main::getInstance()->getDataFolder() . $player->getName().".yml");
        }
        ProgressDataAPI::getInstance()->setCache(Main::getInstance()->getDataFolder() . $player->getName() . ".yml", $player);
    }

}