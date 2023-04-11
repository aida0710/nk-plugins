<?php

declare(strict_types = 0);

namespace bbo51dog\anticheat;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

	private static Setting $setting;

	public static function getSetting() : Setting {
		return self::$setting;
	}

	protected function onEnable() : void {
		$config = new Config($this->getDataFolder() . 'Config.yml', Config::YAML, [
			'AntiJumpCommand' => 'command',
			'discord' => [
				'enabled' => false,
				'url' => 'https://discord.com/xxxx',
			],
		]);
		self::$setting = Setting::loadFromArray($config->getAll());
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}
}
