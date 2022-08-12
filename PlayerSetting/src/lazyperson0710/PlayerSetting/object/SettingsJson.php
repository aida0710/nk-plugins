<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object;

use JsonException;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class SettingsJson {

    use SingletonTrait;

    const FILE_NAME = 'player_settings.json';

    protected Config $config;

    public function init(string $dir): void {
        $this->config = new Config($dir . DIRECTORY_SEPARATOR . self::FILE_NAME, Config::JSON);
    }

    public function input(PlayerSettingPool $pool): void {
        $data_arr = [];
        foreach ($pool->getAll() as $player_setting) {
            $data_arr[$player_setting->getXuid()] = $player_setting->toArray();
        }
        $this->config->setAll($data_arr);
    }

    /**
     * @param PlayerSettingPool $pool
     * @return void
     */
    public function output(PlayerSettingPool $pool): void {
        $data_arr = $this->config->getAll();
        $pool->clear();
        foreach ($data_arr as $xuid => $settings_arr) {
            $player_setting = new PlayerSetting($xuid);
            foreach ($settings_arr as $setting_class => $value) {
                $setting = new $setting_class;
                $setting->setValue($value);
                $player_setting->setSetting($setting);
            }
            $pool->register($player_setting);
        }
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function save(): void {
        $this->config->save();
    }
}