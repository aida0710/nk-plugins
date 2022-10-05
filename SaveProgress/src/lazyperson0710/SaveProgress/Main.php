<?php

namespace lazyperson0710\SaveProgress;

use lazyperson0710\ticket\SaveTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

    use SingletonTrait;

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new QuitEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new JoinEvent(), $this);
        $this->getServer()->getCommandMap()->registerAll("SaveProgress", [
            //コマンドを追加
        ]);
        $this->getScheduler()->scheduleRepeatingTask(new SaveTask(), 20 * 60);
    }

    protected function onDisable(): void {
        //todo all data save
    }

    /* public Config $config;
     public array $data = [];

     public function onEnable(): void {
         $this->getServer()->getPluginManager()->registerEvents(new JoinEvent, $this);
         $this->config = new Config($this->getDataFolder() . "s.yml", Config::YAML, SettingData::DefaultData);
         //idea セーブデータの保存はプレイヤー名のファイルに各セーブデータを個別に突っ込む感じで
         // 3分に一回保存するようにする
         //$setting_file = SettingsJson::getInstance();
         //$setting_file->init($this->getDataFolder());
         //$setting_file->output(PlayerSettingPool::getInstance());
         //$this->getServer()->getCommandMap()->registerAll("playerSetting", [
         //    new SettingCommand(),
         //]);
     }*/
}
