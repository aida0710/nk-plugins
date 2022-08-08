<?php
declare(strict_types=1);

namespace lazyperson0710\PlayerSetting\object;

use pocketmine\player\Player;

class PlayerSetting{
	/**
	 * @var array<ISetting>
	 */
	protected array $settings = [];
	protected string $xuid;

	protected function init():void{

	}

	public function __construct(Player $player){
		$xuid = $player->getXuid();

		if($xuid === '') throw new \InvalidArgumentException($player->getName().' is not signed in xbox');
		$this->xuid = $xuid;
		$this->init();
	}

	public function getXuid():string{
		return $this->xuid;
	}

	protected function register(ISetting $setting):void{
		if(isset($this->settings[$setting->getName()])) throw new \LogicException($setting->getName().' is already registered');
		$this->settings[$setting->getName()] = $setting;
	}

	public function getSetting(string $setting_name):?ISetting{
		return $this->settings[$setting_name]?? null;
	}

	public function getSettingNonNull(string $setting_name):ISetting{
		if(!isset($this->settings[$setting_name])) throw new \RuntimeException($setting_name.' is not exists');
		return $this->settings[$setting_name];
	}

	public function getAll():array{
		return $this->settings;
	}

	public function toArray():array{
		$settings_arr = [];

		foreach($this->getAll() as $setting){
			$settings_arr[$setting->getName()] = $setting->getValue();
		}
		return $settings_arr;
	}
}