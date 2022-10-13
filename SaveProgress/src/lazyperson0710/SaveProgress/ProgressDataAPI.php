<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class ProgressDataAPI {

    use SingletonTrait;

    private array $cache = [];

    public function createData(Player $player): bool {
        if ($this->dataExists($player) === false) {
            $this->setCache($player);
            $this->cache[$player->getName()][] = SettingData::DefaultData;
            var_dump($this->cache);
            return true;
        } else return false;
    }

    public function dataSave(Player $player): bool {
        $tempConfig = $this->cache[$player->getName()]['config'];
        if ($tempConfig instanceof Config) {
            $temp = $this->cache[$player->getName()];
            unset($temp['config']);
            $tempConfig->setAll($temp);
            $tempConfig->save();
            return true;
        }
        return false;
    }

    public function setCache(Player $player): void {
        var_dump(Main::$DataFolder);
        $this->cache[$player->getName()] = ['config' => new Config(Main::$DataFolder . $player->getName() . ".yml", Config::YAML)];
    }

    public function dataExists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }
}