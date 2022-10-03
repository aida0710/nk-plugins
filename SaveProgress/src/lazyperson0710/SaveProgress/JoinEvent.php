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
        Main::getInstance()->data[$player->getName()][] = SettingData::DefaultData;
        Main::getInstance()->data[$name] = new Config(Main::getInstance()->getDataFolder() . str_replace([" ", "　"], "_", $player->getName()), Config::YAML, SettingData::DefaultData
        Main::getInstance()->data[$name]->save();
    }

}