<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class ProgressDataAPI {

    use SingletonTrait;

    private array $cache;
    private Config $config;

    public function setCache($datafile, Player $player): void {
        $this->config[$player->getName()] = new Config($datafile, Config::YAML);
        $this->cache[] = $this->config->getAll();
        //おそらくこれで$this->cache[$player->getName()]にプレイヤーのデータが入るはず
        //note loginしたときにデータを読み込む形で
        // 退出時にデータ保存かな、たぶんそれが一番楽、下のループの必要ないしね
    }

    //hack この書き込み方だとかなりのラグが生まれる可能性があります
    // 別の方法を模索する必要がありますが思いつかんのでとりあえずこれで
    // プレイヤーの名前で個別に保存したい
    // 非同期が現実的かな。。
    //todo 退出時にデータ保存、後でチェックして
    public function dataSave(Player $player, ?bool $allData = false): bool {
        try {
            //foreach ($this->cache as $name => $data) {
            //    $tempConfig = new Config(Main::getInstance()->getDataFolder() . $name . ".yml", Config::YAML);
            //    $tempConfig->setAll($name[$data]);
            //    $tempConfig->save();
            //}
            $this->config->setAll($this->cache[$player->getName()]);
            $this->config->save();
            //warning これだとプレイヤー一人が保存されたら全員なくなりそう
            return true;
        } catch (\JsonException $e) {
            Server::getInstance()->getLogger()->warning($e->getMessage());
            return false;
        }
    }

    public function createData(Player $player): bool {
        if ($this->dataExists($player) === false) {
            $this->cache[$player->getName()] = SettingData::DefaultData;
            //データを書き換えるときに整合性のチェックも行いたい
            return true;
        } else return false;
    }

    public function dataExists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }
}