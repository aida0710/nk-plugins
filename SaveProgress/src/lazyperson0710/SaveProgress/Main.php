<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

    use SingletonTrait;

    public Config $config;
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
    }

    public function getFolder(Player $player): string {
        $sub = substr($name, 0, 1);
        $folder = $this->getDataFolder() . $upper . '/';
        if (!file_exists($folder)) mkdir($folder);
        $lower = strtolower($name);
        return $folder .= $lower . '.json';
    }

}
