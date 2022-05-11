<?php

namespace lazyperson710\itemEdit;

use lazyperson710\itemEdit\command\ItemEditCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->registerAll("ItemEdit", [
            new ItemEditCommand(),
        ]);
    }
}
