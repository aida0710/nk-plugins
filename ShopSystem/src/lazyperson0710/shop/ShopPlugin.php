<?php

declare(strict_types = 1);

namespace lazyperson0710\shop;

use lazyperson0710\shop\command\EffectShopCommand;
use lazyperson0710\shop\command\EnchantShopCommand;
use lazyperson0710\shop\command\ItemShopCommand;
use lazyperson0710\shop\database\EffectShopAPI;
use lazyperson0710\shop\database\EnchantShopAPI;
use lazyperson0710\shop\database\ItemShopAPI;
use pocketmine\plugin\PluginBase;

class ShopPlugin extends PluginBase {

    protected function onDisable() : void {
    }

    protected function onEnable() : void {
        ItemShopAPI::getInstance()->init();
        EffectShopAPI::getInstance()->init();
        EnchantShopAPI::getInstance()->init();
        $this->getServer()->getCommandMap()->registerAll('shopSystem', [
            new ItemShopCommand(),
            new EnchantShopCommand(),
            new EffectShopCommand(),
        ]);
    }
}
