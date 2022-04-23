<?php

namespace deceitya\levelShop;

use deceitya\levelShop\command\Shop1Command;
use deceitya\levelShop\command\Shop2Command;
use deceitya\levelShop\command\Shop3Command;
use deceitya\levelShop\command\Shop4Command;
use deceitya\levelShop\command\Shop5Command;
use deceitya\levelShop\command\Shop6Command;
use deceitya\levelShop\command\Shop7Command;
use deceitya\levelShop\form\element\SecondBackFormButton;
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
            //new InvSellCommand(),
        ]);
    }
}
