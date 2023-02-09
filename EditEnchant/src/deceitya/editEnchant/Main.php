<?php

declare(strict_types = 1);
namespace deceitya\editEnchant;

use deceitya\editEnchant\command\DeleteCommand;
use deceitya\editEnchant\command\ReductionCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	public function onEnable() : void {
		$this->getServer()->getCommandMap()->registerAll('editen', [
			new ReductionCommand(),
			new DeleteCommand(),
		]);
	}
}
