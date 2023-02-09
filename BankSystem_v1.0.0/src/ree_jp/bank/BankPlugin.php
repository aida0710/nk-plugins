<?php

declare(strict_types = 1);
namespace ree_jp\bank;

use pocketmine\plugin\PluginBase;
use ree_jp\bank\command\BankCommand;
use ree_jp\bank\event\EventListener;

class BankPlugin extends PluginBase {

	/** @var BankPlugin */
	private static $instance;

	static function getInstance() : BankPlugin {
		return self::$instance;
	}

	public function onLoad() : void {
		self::$instance = $this;
		parent::onLoad();
	}

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$this->getServer()->getCommandMap()->register("bank", new BankCommand());
		parent::onEnable();
	}
}
