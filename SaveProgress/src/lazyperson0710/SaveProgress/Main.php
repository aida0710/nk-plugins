<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected function onEnable(): void {
        //idea セーブデータの保存はプレイヤー名のファイルに各セーブデータを個別に突っ込む感じで
        // 3分に一回保存するようにする
        //$setting_file = SettingsJson::getInstance();
        //$setting_file->init($this->getDataFolder());
        //$setting_file->output(PlayerSettingPool::getInstance());
        //$this->getServer()->getCommandMap()->registerAll("playerSetting", [
        //    new SettingCommand(),
        //]);
    }

}
