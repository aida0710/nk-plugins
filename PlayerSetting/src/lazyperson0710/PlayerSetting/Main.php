<?php

namespace lazyperson0710\PlayerSetting;

use lazyperson0710\PlayerSetting\command\CheckCommand;
use lazyperson0710\PlayerSetting\command\TestCommand;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\SettingsJson;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected function onEnable(): void {
        $setting_file = SettingsJson::getInstance();
        $setting_file->init($this->getDataFolder());
        $setting_file->output(PlayerSettingPool::getInstance());
        $this->getServer()->getCommandMap()->registerAll("playerSetting", [
            new TestCommand(),
            new CheckCommand(),
        ]);
    }

    /**
     * @throws \JsonException
     */
    protected function onDisable():void{
        $setting_file = SettingsJson::getInstance();
        $setting_file->input(PlayerSettingPool::getInstance());
        $setting_file->save();
    }
}
