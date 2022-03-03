<?php

namespace deceitya\ecoshop;

use deceitya\ecoshop\command\Shop1Command;
use deceitya\ecoshop\command\Shop2Command;
use deceitya\ecoshop\command\Shop3Command;
use deceitya\ecoshop\command\Shop4Command;
use deceitya\ecoshop\command\Shop5Command;
use deceitya\ecoshop\command\Shop6Command;
use deceitya\ecoshop\command\Shop7Command;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getCommandMap()->registerAll("levelshop", [
            new Shop1Command(),
            new Shop2Command(),
            new Shop3Command(),
            new Shop4Command(),
            new Shop5Command(),
            new Shop6Command(),
            new Shop7Command(),
        ]);
    }
}
