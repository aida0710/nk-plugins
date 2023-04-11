<?php

declare(strict_types = 0);

namespace lazyperson710\edit;

use lazyperson710\edit\command\ItemEditCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

	protected function onEnable() : void {
		$this->saveDefaultConfig();
		$this->getServer()->getCommandMap()->registerAll('ItemEdit', [
			new ItemEditCommand(),
		]);
	}
}
