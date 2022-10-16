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
            $this->cache[$player->getName()] = ['config' => new Config(Main::$DataFolder . $player->getName() . ".yml", Config::YAML)];
            if (!empty($this->cache[$player->getName()]["config"]->getAll())) {
                $this->cache[$player->getName()][] = $this->cache[$player->getName()]["config"]->getAll();
            } else {
                $this->cache[$player->getName()][] = SettingData::DefaultData;
            }
            $this->dataSave($player);
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

    public function dataExists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }

    private function dataUpdate(Player $player, Config $config) {
        $temp = $config->getAll();
        //keyを階層ごとにないのがないか確認していく
        foreach (SettingData::DefaultData as $key => $value) {
            if (array_key_exists($key, $temp) === false) {
                $temp[$key] = $value;
            } else {
                foreach ($value as $key2 => $value2) {
                    if (array_key_exists($key2, $temp[$key]) === false) {
                        $temp[$key][$key2] = $value2;
                    }
                }
            }
        }
        $this->cache[$player->getName()][] = $temp;
    }
}