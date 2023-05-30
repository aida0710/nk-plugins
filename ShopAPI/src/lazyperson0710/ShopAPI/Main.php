<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopAPI;

use lazyperson0710\ShopAPI\command\EffectShopCommand;
use lazyperson0710\ShopAPI\command\EnchantShopCommand;
use lazyperson0710\ShopAPI\command\InvSellCommand;
use lazyperson0710\ShopAPI\command\ShopCommand;
use lazyperson0710\ShopAPI\database\EffectShopAPI;
use lazyperson0710\ShopAPI\database\EnchantShopAPI;
use lazyperson0710\ShopAPI\database\LevelShopAPI;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	public function onEnable() : void {
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
