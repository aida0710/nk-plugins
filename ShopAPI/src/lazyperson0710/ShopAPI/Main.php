<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem;

use lazyperson0710\ShopSystem\command\EffectShopCommand;
use lazyperson0710\ShopSystem\command\EnchantShopCommand;
use lazyperson0710\ShopSystem\command\InvSellCommand;
use lazyperson0710\ShopSystem\command\ShopCommand;
use lazyperson0710\ShopSystem\database\EffectShopAPI;
use lazyperson0710\ShopSystem\database\EnchantShopAPI;
use lazyperson0710\ShopSystem\database\LevelShopAPI;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	protected function onEnable() : void {
		LevelShopAPI::getInstance()->init();
		EffectShopAPI::getInstance()->init();
		EnchantShopAPI::getInstance()->init();
		$this->getServer()->getCommandMap()->registerAll('levelshop', [
			new ShopCommand(),
			new InvSellCommand(),
			new EnchantShopCommand(),
			new EffectShopCommand(),
		]);
	}
}
