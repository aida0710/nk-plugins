<?php
declare(strict_types=1);

namespace lazyperson0710\PlayerSetting\object;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class SettingsJson{
	use SingletonTrait;

	const FILE_NAME = 'player_settings.json';

	protected Config $config;

	public function __construct(){
		$this->config = new Config(self::FILE_NAME, Config::JSON);
	}

	public function save(PlayerSettingPool $pool):void{
		$data_arr = [];

		foreach($pool->getAll() as $player_setting){
			$data_arr[$player_setting->getXuid()] = $player_setting->toArray();
		}
		$this->config->setAll($data_arr);
	}
}