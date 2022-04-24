<?php

namespace lazyperson710\itemEdit;

use lazyperson710\itemEdit\command\nbtEditCommand;
use lazyperson710\itemEdit\command\UnbreakableCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->registerAll("ItemEdit", [
            new nbtEditCommand(),
            new UnbreakableCommand(),
        ]);
    }
}
