<?php

namespace lazyperson0710\ticket;

use Exception;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use ree_jp\stackstorage\api\StackStorageAPI;
use ree_jp\stackstorage\libs\poggit\libasynql\SqlError;

class TicketAPI {

    private static TicketAPI $instance;
    private int $stCount;
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

    public function save(): void {
        try {
            $this->config->setAll($this->cache);
            $this->config->save();
        } catch (Exception $e) {
            return;
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

    public function checkData(Player $player): int {
        if ($this->exists($player) === true) {
            return var_export($this->cache[$player->getName()]);
        } else {
            $this->createData($player);
            return 0;
        }
    }

    public function containsTicket(Player $player, int $amount): bool {
        if ($this->exists($player) === true) {
            if ($this->checkData($player) <= $amount) {
                return true;
            } else return false;
        } else return false;
    }

    public function setTicket(Player $player, int $setInt): bool|int {
        if ($this->exists($player) === true) {
            $this->cache[$player->getName()] = $setInt;
            return $setInt;
        } else return false;
    }

    public function addTicket(Player $player, int $increase): bool|int {
        if ($this->exists($player) === true) {
            $int = var_export($this->cache[$player->getName()]);
            $increase += $int;
            $this->cache[$player->getName()] = $increase;
            return $increase;
        } else return false;
    }

    public function reduceTicket(Player $player, int $reduce): bool|int {
        if ($this->exists($player) === true) {
            $int = var_export($this->cache[$player->getName()]);
            $reduce -= $int;
            if ($reduce <= 0) {
                $reduce = 0;
            }
            $this->cache[$player->getName()] = $reduce;
            return $reduce;
        } else return false;
    }

    public function replaceTicket(Player $player): bool|int {
        $inventory = $player->getInventory();
        $count = 0;
        for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
            $item = clone $inventory->getItem($i);
            if ($item->getId() !== 465) continue;
            $count += $item->getCount();
            $inventory->remove($item);
        }
        $itemIds = ItemFactory::getInstance()->get(465);
        StackStorageAPI::$instance->getCount($player->getXuid(), $itemIds, function (int $stCount) {
            $this->stCount = $stCount;
        }, function (SqlError $error) {
            var_dump('error ' . $error->getMessage());
        });
        StackStorageAPI::$instance->remove($player->getXuid(), $itemIds);
        $count += $this->stCount;
        if ($count === 0) return false;
        $this->addTicket($player, $count);
        return $count;
    }

}