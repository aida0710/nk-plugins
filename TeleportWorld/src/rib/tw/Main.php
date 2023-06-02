<?php

declare(strict_types = 0);

namespace rib\tw;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use rib\tw\command\TeleportCommand;

class Main extends PluginBase {

    public function onEnable() : void {
        Server::getInstance()->getCommandMap()->register('worldTeleport', new TeleportCommand());
    }

}
