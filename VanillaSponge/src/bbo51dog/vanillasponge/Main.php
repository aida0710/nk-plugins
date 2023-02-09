<?php

declare(strict_types = 1);
namespace bbo51dog\vanillasponge;

use bbo51dog\vanillasponge\block\VanillaSponge;
use pocketmine\block\BlockFactory;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	protected function onEnable() : void {
		BlockFactory::getInstance()->register(new VanillaSponge(), true);
	}
}
