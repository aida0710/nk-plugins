<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools;

use lazyperson0710\miningtools\command\MiningToolsCommand;
use lazyperson0710\miningtools\eventListener\BreakEventListener;
use lazyperson0710\miningtools\eventListener\JoinEventListener;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use function file_get_contents;
use function json_decode;

class Main extends PluginBase implements Listener {

	public const NETHERITE_SHOVEL = 744;
	public const NETHERITE_PICKAXE = 745;
	public const NETHERITE_AXE = 746;

	private static self $instance;

	public array $config;
	private array $allData;

	static array $flag = [];

	static array $diamond;
	static array $netherite;

	protected function onEnable() : void {
		$this->saveResource('config.json');
		$this->getServer()->getPluginManager()->registerEvents(new BreakEventListener(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new JoinEventListener(), $this);
		$this->getServer()->getCommandMap()->registerAll('MiningTools', [
			new MiningToolsCommand(),
		]);
		$this->allData = json_decode(file_get_contents($this->getDataFolder() . 'config.json'), true);
		self::$diamond = Main::getInstance()->dataAcquisition('diamond');
		self::$netherite = Main::getInstance()->dataAcquisition('netherite');
	}

	public function dataAcquisition($tools) : array {
		return $this->allData[0][$tools];
	}

	protected function onLoad() : void {
		self::$instance = $this;
	}

	public static function getInstance() : Main {
		return self::$instance;
	}

}
