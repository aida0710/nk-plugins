<?php

namespace lazyperson0710\ticket;

use JsonException;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use ree_jp\stackstorage\api\StackStorageAPI;

class TicketAPI {

    private static TicketAPI $instance;
    /**
     * @var array|string[]
     */
    private array $cache;
    private Config $config;

    private function __construct(string $dataFile) {
        $this->config = new Config($dataFile, Config::YAML, [
            "player" => 0,
        ]);
        $this->cache = $this->config->getAll();
    }

    public static function init(string $dataFile): void {
        self::$instance = new TicketAPI($dataFile);
    }

    public static function getInstance(): TicketAPI {
        return self::$instance;
    }

    public function setCache(): void {
        $this->cache = $this->config->getAll();
    }

    public function save(): bool {
        try {
            $this->config->setAll($this->cache);
            $this->config->save();
            return true;
        } catch (JsonException $e) {
            Server::getInstance()->getLogger()->warning($e->getMessage());
            return false;
        }
    }

    public function exists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }

    public function createData(Player $player): bool {
        if ($this->exists($player) === false) {
            $this->cache += array($player->getName() => 0);
            return true;
        } else return false;
    }

    public function checkData(Player $player): int|bool {
        if ($this->exists($player) === true) {
            return (int)$this->cache[$player->getName()];
        } else return false;
    }

    public function containsTicket(Player $player, int $amount): bool {
        if ($this->exists($player) === false) return false;
        if ($this->checkData($player) <= $amount) {
            return true;
        } else return false;
    }

    public function setTicket(Player $player, int $setInt): bool|int {
        if ($this->exists($player) === false) return false;
        $this->cache[$player->getName()] = $setInt;
        return $setInt;
    }

    public function addTicket(Player $player, int $increase): bool|int {
        if ($this->exists($player) === false) return false;
        $int = $this->cache[$player->getName()];
        $result = $increase + $int;
        $this->cache[$player->getName()] = $result;
        return $result;
    }

    public function reduceTicket(Player $player, int $reduce): bool|int {
        if ($this->exists($player) === false) return false;
        if ($reduce <= 0) return false;
        $int = $this->cache[$player->getName()];
        $result = $int - $reduce;
        if ($result >= 0) {
            $this->cache[$player->getName()] = $result;
            return $result;
        } else return false;
    }

    public function replaceInventoryTicket(Player $player): int {
        if ($this->exists($player) === false) return 0;
        $inventory = $player->getInventory();
        $count = 0;
        for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
            $item = clone $inventory->getItem($i);
            if ($item->getId() !== VanillaItems::NAUTILUS_SHELL()->getId()) continue;
            $count += $item->getCount();
            $inventory->clear($i);
        }
        if ($count === 0) return 0;
        $this->addTicket($player, $count);
        return $count;
    }

    public function replaceStackStorageTicket(Player $player): int {
        if ($this->exists($player) === false) return 0;
        StackStorageAPI::$instance->getCount($player->getXuid(), clone VanillaItems::NAUTILUS_SHELL(), function (int $stCount) use ($player) {
            if ($stCount <= 0) return 0;
            $item = clone VanillaItems::NAUTILUS_SHELL();
            $item->setCount($stCount);
            StackStorageAPI::$instance->remove($player->getXuid(), $item);
            $this->addTicket($player, $stCount);
            return $stCount;
        }, function () use ($player) {
            return 0;
        });
        return 0;
    }
}