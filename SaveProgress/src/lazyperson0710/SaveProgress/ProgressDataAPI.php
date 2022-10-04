<?php

namespace lazyperson0710\SaveProgress;

use JetBrains\PhpStorm\Pure;
use JsonException;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class ProgressDataAPI {

    use SingletonTrait;

    /**
     * @var array|string[]
     */
    private array $cache;
    private Config $allConfigData;

    public function setCache($dataFile): void {
        $this->allConfigData = new Config($dataFile, Config::YAML);
        $this->cache = $this->allConfigData->getAll();
    }

    //hack この書き込み方だとかなりのラグが生まれる可能性があります
    // 別の方法を模索する必要がありますが思いつかんのでとりあえずこれで
    // プレイヤーの名前で個別に保存したい
    public function dataSave(): bool {
        try {
            //$this->config->setAll($this->cache);
            foreach ($this->cache as $name => $data) {
                $tempConfig = new Config(Main::getInstance()->getDataFolder() . $name . ".yml", Config::YAML);
                $tempConfig->setAll($name[$data]);
                $tempConfig->save();
            }
            $this->allConfigData->save();
            return true;
        } catch (JsonException $e) {
            Server::getInstance()->getLogger()->warning($e->getMessage());
            return false;
        }
    }

    public function createData(Player $player): bool {
        if ($this->dataExists($player) === false) {
            $this->cache += [$player->getName() => 0];
            return true;
        } else return false;
    }

    #[Pure] public function dataExists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }
}