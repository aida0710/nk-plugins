<?php

namespace stackall;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->saveResource("config.yml");
        $this->reloadConfig();
        Server::getInstance()->getCommandMap()->registerAll("stackall", [new StallCommand($this->getConfig())]);
    }
}
