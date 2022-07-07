<?php

namespace deceitya\ShopAPI;

use deceitya\ShopAPI\command\EffectShopCommand;
use deceitya\ShopAPI\command\EnchantShopCommand;
use deceitya\ShopAPI\command\InvSellCommand;
use deceitya\ShopAPI\command\ShopCommand;
use deceitya\ShopAPI\database\EffectShopAPI;
use deceitya\ShopAPI\database\EnchantShopAPI;
use deceitya\ShopAPI\database\LevelShopAPI;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        LevelShopAPI::getInstance()->init();
        EffectShopAPI::getInstance()->init();
        EnchantShopAPI::getInstance()->init();
        $this->getServer()->getCommandMap()->registerAll("levelshop", [
            new ShopCommand(),
            new InvSellCommand(),
            new EnchantShopCommand(),
            new EffectShopCommand(),
        ]);
    }
}
