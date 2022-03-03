<?php

declare(strict_types=1);
namespace space\yurisi;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use space\yurisi\Commands\marketCommand;

class Market extends PluginBase {

    private static Market $market;

    public static function getInstance(): self {
        return self::$market;
    }

    public function onEnable(): void {
        $this->getServer()->getCommandMap()->register("market", new marketCommand());
        if (!(file_exists($this->getDataFolder()))) @mkdir($this->getDataFolder());
        new Config($this->getDataFolder() . "market.yml", Config::YAML, array());
        self::$market = $this;
    }

}