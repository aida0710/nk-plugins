<?php

namespace lazyperson0710\ticket;

use JetBrains\PhpStorm\Pure;
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

    #[Pure] public function dataExists(Player $player): bool {
        return array_key_exists($player->getName(), $this->cache);
    }

    public function createData(Player $player): bool {
        if ($this->dataExists($player) === false) {
            $this->cache += array($player->getName() => 0);
            return true;
        } else return false;
    }

    #[Pure] public function checkData(Player $player): int|bool {
        if ($this->dataExists($player) === true) {
            return (int)$this->cache[$player->getName()];
        } else return false;
    }

    #[Pure] public function containsTicket(Player $player, int $amount): bool {
        if ($this->dataExists($player) === false) return false;
        if ($this->checkData($player) <= $amount) {
            return true;
        } else return false;
    }

    public function setTicket(Player $player, int $setInt): bool|int {
        if ($this->dataExists($player) === false) return false;
        $this->cache[$player->getName()] = $setInt;
        return $setInt;
    }

    /*
     * ticketの数を増やす
     * データが存在しない場合はfalseを返す//intだけに絞ってもいいかも
     *
     * */
    public function addTicket(Player $player, int $increase): bool|int {
        if ($this->dataExists($player) === false) return false;
        $int = $this->cache[$player->getName()];
        $result = $increase + $int;
        $this->cache[$player->getName()] = $result;
        return $result;
    }

    /*
     * ticketの数を減らす
     * データが存在しない場合はfalseを返す//intだけに絞ってもいいかも
     * 指定値が0以下の場合もfalseを返す
     * 結果の値が0以下の場合は0で返却する
     * 正常に動作した場合は1以上の数字を返す
     * */
    public function reduceTicket(Player $player, int $reduce): bool|int {
        if ($this->dataExists($player) === false) return false;
        if ($reduce <= 0) return false;
        $int = $this->cache[$player->getName()];
        $result = $int - $reduce;
        if ($result >= 0) {
            $this->cache[$player->getName()] = $result;
            return $result;
        } else {
            $this->cache[$player->getName()] = 0;
            return 0;
        }
    }

    /*
     * inventory内のticket置換
     * 変換できる個数を最終的に返す為、受け取り側は0か1以上で判断して欲しいです
     * また、データが存在しない場合も0を返す
     * */
    public function replaceInventoryTicket(Player $player): int {
        if ($this->dataExists($player) === false) return 0;
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

    /*
     * stackStorageのticket置換
     * 変換できる個数を最終的に返す為、受け取り側は0か1以上で判断して欲しいです
     * また、データが存在しない場合も0を返す
     * */
    public function replaceStackStorageTicket(Player $player): int {
        if ($this->dataExists($player) === false) return 0;
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