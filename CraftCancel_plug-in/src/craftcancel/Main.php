<?php

namespace craftcancel;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    public function onEnable(): void {
        $config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, ['ItemIds' => [0, 1, 2]]);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($config->get('ItemIds')), $this);
    }
}
