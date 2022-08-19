<?php

namespace deceitya\miningtools\setting;

use JsonException;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class MiningToolSettings {

    use SingletonTrait;

    public function onLoad(): void {
        self::setInstance($this);
    }

    /**
     * @var array|string[]
     */
    private array $cache;
    private Config $config;

    public function setCache($dataFile): void {
        $this->config = new Config($dataFile, Config::YAML, [
            "player" => [
                "草ブロックを土にする" => false,
                "丸石を石にする" => false,
                "花崗岩を石にする" => false,
                "閃緑岩を石にする" => false,
                "安山岩を石にする" => false,
                "鉄の自動精錬" => false,
                "金の自動精錬" => false,
                "砂をガラスにする" => false,
            ],
        ]);
        $this->cache = $this->config->getAll();
    }

    public function dataSave(): bool {
        try {
            $this->config->setAll($this->cache);
            $this->config->save();
            return true;
        } catch (JsonException $e) {
            Server::getInstance()->getLogger()->warning($e->getMessage());
            return false;
        }
    }

    public function createData(Player $player): bool {
        if ($this->dataExists($player) === false) {
            $this->cache += [$player->getName() => [
                "草ブロックを土にする" => false,
                "丸石を石にする" => false,
                "花崗岩を石にする" => false,
                "閃緑岩を石にする" => false,
                "安山岩を石にする" => false,
                "鉄の自動精錬" => false,
                "金の自動精錬" => false,
                "砂をガラスにする" => false,
            ],
            ];
            return true;
        } else return false;
    }

    public function dataExists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }

    public function checkData(Player $player, string $setSetting): bool {
        if ($this->dataExists($player) === true) {
            return $this->cache[$player->getName()][$setSetting];
        } else return false;
    }

    public function setData(Player $player, string $setSetting, bool $bool): bool {
        if ($this->dataExists($player) === false) return false;
        $this->cache[$player->getName()][$setSetting] = $bool;
        return $this->cache[$player->getName()][$setSetting];
    }

}