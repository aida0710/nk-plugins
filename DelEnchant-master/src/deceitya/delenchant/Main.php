<?php

namespace deceitya\delenchant;


use deceitya\delenchant\command\DeleteCommand;
use deceitya\delenchant\command\ReductionCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getCommandMap()->registerAll("editen", [
            new ReductionCommand(),
            new DeleteCommand(),
        ]);
    }
}