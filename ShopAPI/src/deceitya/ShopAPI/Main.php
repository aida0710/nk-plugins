<?php

namespace deceitya\ShopAPI;

use deceitya\ShopAPI\command\EffectShopCommand;
use deceitya\ShopAPI\command\EnchantShopCommand;
use deceitya\ShopAPI\command\InvSellCommand;
use deceitya\ShopAPI\command\Shop1Command;
use deceitya\ShopAPI\command\Shop2Command;
use deceitya\ShopAPI\command\Shop3Command;
use deceitya\ShopAPI\command\Shop4Command;
use deceitya\ShopAPI\command\Shop5Command;
use deceitya\ShopAPI\command\Shop6Command;
use deceitya\ShopAPI\command\Shop7Command;
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
            new InvSellCommand(),
            new EnchantShopCommand(),
            new EffectShopCommand(),
        ]);
    }
}
