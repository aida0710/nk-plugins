<?php

declare(strict_types=1);
namespace space\yurisi\Config;

use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use space\yurisi\Market;

class YamlConfig extends Config {

    private static Config $config;

    public function __construct() {
        parent::__construct(Market::getInstance()->getDataFolder() . "market_v2.yml", Config::YAML);
        self::$config = $this;
    }

    public static function getItem(int $id): ?Item {
        if (self::$config->exists($id)) {
            return Item::nbtDeserialize(unserialize(self::$config->get($id)["nbt"]));
        }
        return null;
    }

    public function registerItem(int $price, Player $player, bool $bool, CompoundTag $nbt) {
        $id = $this->getRegisterId();
        $this->set($id, array(
            "id" => $id,
            "price" => $price,
            "player" => $player->getName(),
            "public" => $bool,
            "nbt" => $nbt
        ));
        $this->save();
    }

    private function getRegisterId(): int {
        $all_data = $this->getAll();
        SameId:
        $id = rand(1000, 9999);//idが重複しないようにしたい
        foreach ($all_data as $data) {
            //var_dump("現在存在するid{$data["id"]}");
            if ($id === $data["id"]) {
                //var_dump("重複してたらSameIdに飛んでforeachをもう一度回す->{$id}");
                goto SameId;//重複してたらSameIdに飛んでforeachをもう一度回す
            }
        }
        //var_dump("重複してなかったらそのまま登録->{$id}");//重複してなかったらそのまま登録
        return $id;
    }

    public function removeItem(int $id) {
        if ($this->exists($id)) {
            $this->remove($id);
            $this->save();
        }
    }

    public function getMarketItem(int $id, int $damage = 0): array {
        $adata = $this->getAll();
        $items = [];
        foreach ($adata as $data) {
            if (isset($data["id"])) {
                $item = Item::nbtDeserialize(unserialize($data["nbt"]));
                if ($item->getId() == $id) {
                    if (!$data["public"]) {
                        if (self::isTools($id)) {
                            $items[] = $data["id"];
                        } else {
                            if ($item->getMeta() == $damage) {
                                $items[] = $data["id"];
                            }
                        }
                    }
                }
            }
        }
        return $items;
    }

    public static function isTools(int $id) {
        $item = ItemFactory::getInstance()->get($id, 0, 1);
        if ($item instanceof Durable) {
            return true;
        }
        return false;
    }

    public function getAllMarket(): array {
        $adata = $this->getAll();
        $items = [];
        foreach ($adata as $data) {
            if (!$data["public"]) {
                $items[] = $data["id"];
            }
        }
        return $items;
    }

    public function getPrivateAllMarket(): array {
        $adata = $this->getAll();
        $items = [];
        foreach ($adata as $data) {
            $items[] = $data["id"];
        }
        return $items;
    }

    public function getMarketPlayer(string $name): array {
        $adata = $this->getAll();
        $items = [];
        foreach ($adata as $data) {
            if ($data["player"] == $name) {
                $items[] = $this->getMarketData($data["id"]);
            }
        }
        return $items;
    }

    public function getMarketData(int $id): array {
        if (!$this->exists($id)) return [];
        return $this->get($id);
    }
}