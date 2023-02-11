<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object;

use InvalidArgumentException;
use lazyperson0710\PlayerSetting\object\settings\donation\Donation_10000;
use lazyperson0710\PlayerSetting\object\settings\donation\Donation_1500;
use lazyperson0710\PlayerSetting\object\settings\miningTools\AndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\CobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\DioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\IronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\SandToGlassSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingAndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingCobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingDioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingIronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingSandToGlassSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\BossBarColorSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\CoordinateSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DestructionSoundSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DiceMessageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\DirectDropItemStorageSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\EnduranceWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\JoinItemsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\LevelUpDisplaySetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsDestructionEnabledWorldsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\MiningToolsEnduranceWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\OnlinePlayersEffectsSetting;
use lazyperson0710\PlayerSetting\object\settings\normal\PayCommandUseSetting;
use LogicException;
use RuntimeException;

class PlayerSetting {

	/** @var array<Setting> */
	protected array $settings = [];
	protected string $xuid;

	protected function init() : void {
		//normal
		$this->register(new CoordinateSetting());
		$this->register(new JoinItemsSetting());
		$this->register(new DirectDropItemStorageSetting());
		$this->register(new LevelUpDisplaySetting());
		$this->register(new EnduranceWarningSetting());
		$this->register(new MiningToolsEnduranceWarningSetting());
		$this->register(new DestructionSoundSetting());
		$this->register(new DiceMessageSetting());
		$this->register(new PayCommandUseSetting());
		$this->register(new OnlinePlayersEffectsSetting());
		$this->register(new MiningToolsDestructionEnabledWorldsSetting());
		$this->register(new BossBarColorSetting());
		//miningTools
		$this->register(new AndesiteToStoneSetting());
		$this->register(new CobblestoneToStoneSetting());
		$this->register(new DioriteToStoneSetting());
		$this->register(new GoldIngotSetting());
		$this->register(new GraniteToStoneSetting());
		$this->register(new GrassToDirtSetting());
		$this->register(new IronIngotSetting());
		$this->register(new SandToGlassSetting());
		//miningToolsEnablingSetting
		$this->register(new EnablingAndesiteToStoneSetting());
		$this->register(new EnablingCobblestoneToStoneSetting());
		$this->register(new EnablingDioriteToStoneSetting());
		$this->register(new EnablingGoldIngotSetting());
		$this->register(new EnablingGraniteToStoneSetting());
		$this->register(new EnablingGrassToDirtSetting());
		$this->register(new EnablingIronIngotSetting());
		$this->register(new EnablingSandToGlassSetting());
		//donation
		$this->register(new Donation_1500());
		$this->register(new Donation_10000());
	}

	public function __construct(string $xuid) {
		if ($xuid === '') throw new InvalidArgumentException('player is not signed in xbox');
		$this->xuid = $xuid;
		$this->init();
	}

	public function getXuid() : string {
		return $this->xuid;
	}

	protected function register(Setting $setting) : void {
		if (isset($this->settings[$setting->getName()])) throw new LogicException($setting->getName() . ' is already registered');
		$this->settings[$setting->getName()] = $setting;
	}

	public function setSetting(Setting $setting) : void {
		if (!isset($this->settings[$setting->getName()])) throw new LogicException($setting->getName() . ' is not registered');
		$this->settings[$setting->getName()] = $setting;
	}

	public function getSetting(string $setting_name) : ?Setting {
		return $this->settings[$setting_name] ?? null;
	}

	public function getSettingNonNull(string $setting_name) : Setting {
		if (!isset($this->settings[$setting_name])) throw new RuntimeException($setting_name . ' is not exists');
		return $this->settings[$setting_name];
	}

	public function getAll() : array {
		return $this->settings;
	}

	public function toArray() : array {
		$settings_arr = [];
		foreach ($this->getAll() as $setting) {
			$settings_arr[$setting::class] = $setting->getValue();
		}
		return $settings_arr;
	}
}
