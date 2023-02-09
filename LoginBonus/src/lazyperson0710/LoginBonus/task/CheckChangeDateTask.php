<?php

declare(strict_types = 1);
namespace lazyperson0710\LoginBonus\task;

use lazyperson0710\LoginBonus\event\JoinPlayerEvent;
use lazyperson0710\LoginBonus\Main;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use function date;

class CheckChangeDateTask extends Task {

	private PluginBase $plugin;
	private string $date;

	public function __construct(PluginBase $plugin) {
		$this->plugin = $plugin;
		$this->date = date('Y/m/d');
	}

	public function onRun() : void {
		if ($this->date !== date('Y/m/d')) {
			$this->date = date('Y/m/d');
			Main::getInstance()->lastBonusDateConfig->setAll([]);
			foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
				JoinPlayerEvent::check($player, true);
			}
			Main::getInstance()->lastBonusDateConfig->save();
		}
	}
}
