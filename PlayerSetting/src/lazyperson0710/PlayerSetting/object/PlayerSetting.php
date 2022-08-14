<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object;

use InvalidArgumentException;
use lazyperson0710\PlayerSetting\object\settings\CoordinateSetting;
use lazyperson0710\PlayerSetting\object\settings\DestructionSoundSetting;
use lazyperson0710\PlayerSetting\object\settings\DiceMessageSetting;
use lazyperson0710\PlayerSetting\object\settings\DirectDropItemStorageSetting;
use lazyperson0710\PlayerSetting\object\settings\EnduranceWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\JoinItemsSetting;
use lazyperson0710\PlayerSetting\object\settings\LevelUpTitleSetting;
use lazyperson0710\PlayerSetting\object\settings\MiningToolsEnduranceWarningSetting;
use lazyperson0710\PlayerSetting\object\settings\OnlinePlayersEffectsSetting;
use lazyperson0710\PlayerSetting\object\settings\PayCommandUseSetting;
use LogicException;
use RuntimeException;

class PlayerSetting {

    /**
     * @var array<Setting>
     */
    protected array $settings = [];
    protected string $xuid;

    protected function init(): void {
        $this->register(new CoordinateSetting);
        $this->register(new JoinItemsSetting);
        $this->register(new DirectDropItemStorageSetting);
        $this->register(new LevelUpTitleSetting);
        $this->register(new EnduranceWarningSetting);
        $this->register(new MiningToolsEnduranceWarningSetting());
        $this->register(new DestructionSoundSetting);
        $this->register(new DiceMessageSetting);
        $this->register(new PayCommandUseSetting);
        $this->register(new OnlinePlayersEffectsSetting);
    }

    public function __construct(string $xuid) {
        if ($xuid === '') throw new InvalidArgumentException('player is not signed in xbox');
        $this->xuid = $xuid;
        $this->init();
    }

    public function getXuid(): string {
        return $this->xuid;
    }

    protected function register(Setting $setting): void {
        if (isset($this->settings[$setting->getName()])) throw new LogicException($setting->getName() . ' is already registered');
        $this->settings[$setting->getName()] = $setting;
    }

    public function setSetting(Setting $setting): void {
        if (!isset($this->settings[$setting->getName()])) throw new LogicException($setting->getName() . ' is not registered');
        $this->settings[$setting->getName()] = $setting;
    }

    public function getSetting(string $setting_name): ?Setting {
        return $this->settings[$setting_name] ?? null;
    }

    public function getSettingNonNull(string $setting_name): Setting {
        if (!isset($this->settings[$setting_name])) throw new RuntimeException($setting_name . ' is not exists');
        return $this->settings[$setting_name];
    }

    public function getAll(): array {
        return $this->settings;
    }

    public function toArray(): array {
        $settings_arr = [];
        foreach ($this->getAll() as $setting) {
            $settings_arr[$setting::class] = $setting->getValue();
        }
        return $settings_arr;
    }
}