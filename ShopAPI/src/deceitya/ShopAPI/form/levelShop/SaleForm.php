<?php

namespace deceitya\ShopAPI\form\levelShop;

use deceitya\ShopAPI\database\LevelShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use ree_jp\stackStorage\api\StackStorageAPI;

class SaleForm implements Form {

    private Item $item;
    private int $price;
    private int $count;
    private int $myMoney;
    private int $storage;
    private LevelShopAPI $api;

    public function __construct(Item $item, int $price, int $count, int $myMoney, int $storage) {
        $this->item = $item;
        $this->price = $price;
        $this->count = $count;
        $this->myMoney = $myMoney;
        $this->storage = $storage;
        $this->api = LevelShopAPI::getInstance();
    }

    public function handleResponse(Player $player, $data): void {
        $api = LevelShopAPI::getInstance();
        if ($data === null || $data[1] === null) {
            $player->sendMessage("§bLevelShop §7>> §a{$this->api->getItemName($this->item->getId(), $this->item->getMeta())}の売却がキャンセルされました");
            return;
        }
        if ($data[1] === '' || !$this->isInteger($data[1]) || (int)floor($data[1]) <= 0) {
            $player->sendMessage('§bLevelShop §7>> §c1以上の整数を入力してください');
            return;
        }
        $count = (int)floor($data[1]);
        $this->item->setCount($count);
        StackStorageAPI::$instance->getCount($player->getXuid(), $this->item, function ($count) use ($player, $data): void {
            $this->storage = $count;
            $this->transaction($player, $data);
        }, function () use ($player, $data): void {
            $this->storage = 0;
            $this->transaction($player, $data);
        });
    }

    private function isInteger($input): bool {
        return (ctype_digit(strval($input)));
    }

    public function transaction(Player $player, array $data): void {
        $count = floor($data[1]);
        $this->item->setCount($count);
        $inventory = $this->countItem($player, $this->item);
        if ($data[2] === true && $this->storage !== 0) {
            if ($count <= $this->storage) {
                $storageResult = $this->buyItemFromStackStorage($player, $this->item, $count);
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $count . "個アイテムが売却され、所持金が{$storageResult}円増えました");
                return;
            }
            $storageItemCount = $count - $this->storage;
            if ($storageItemCount <= $inventory) {
                $storageResult = $this->buyItemFromStackStorage($player, $this->item, $this->storage);
                $inventoryResult = $this->buyItemFromInventory($player, $this->item, $storageItemCount);
                $result = $inventoryResult + $storageResult;
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $this->storage . "個とインベントリから" . $storageItemCount . "個、計" . ($this->storage + $storageItemCount) . "アイテムが売却され、所持金が{$result}円増えました");
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            return;
        }
        if (!$player->getInventory()->contains($this->item)) {
            $storageItemCount = $count - $inventory;
            if ($storageItemCount <= $this->storage) {
                $storageResult = $this->buyItemFromStackStorage($player, $this->item, $storageItemCount); //$this->price * $storageItemCount;
                if ($inventory === 0) {
                    $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $storageItemCount . "個アイテムが売却され、所持金が{$storageResult}円増えました");
                    return;
                }
                $inventoryResult = $this->buyItemFromInventory($player, $this->item, $inventory);
                $result = $inventoryResult + $storageResult;
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $storageItemCount . "個とインベントリから" . $inventory . "個、計" . ($storageItemCount + $inventory) . "アイテムが売却され、所持金が{$result}円増えました");
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            return;
        }
        $this->buyItemFromInventory($player, $this->item, $count);
        $result = $this->price * $count;
        $player->sendMessage("§bLevelShop §7>> §aアイテムが" . $count . "個売却され、所持金が{$result}円増えました");
    }

    public function countItem(Player $player, Item $targetItem): int {
        $inventory = 0;
        for ($i = 0; $i <= 35; $i++) {
            $item = $player->getInventory()->getItem($i);
            if ($targetItem->equals($item)) {
                $inventory += $item->getCount();
            }
        }
        return $inventory;
    }

    public function buyItemFromStackStorage(Player $player, Item $item, int $count): int {
        $item = (clone $item)->setCount($count);
        $storageResult = $this->price * $count;
        StackStorageAPI::$instance->remove($player->getXuid(), $item);
        EconomyAPI::getInstance()->addMoney($player, $storageResult);
        return $storageResult;
    }

    public function buyItemFromInventory(Player $player, Item $item, int $count): int {
        $item = (clone $item)->setCount($count);
        $result = $this->price * $count;
        $player->getInventory()->removeItem($item);
        EconomyAPI::getInstance()->addMoney($player, $this->price * $count);
        return $result;
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'LevelShop',
            'content' => [
                [
                    'type' => 'label',
                    'text' => "売却するアイテム/" . LevelShopAPI::getInstance()->getItemName($this->item->getId(), $this->item->getMeta()) . "\n1つあたりの値段/{$this->price}\n仮想ストレージにある量/{$this->storage}\nインベントリにある数/{$this->count}\n現在の所持金/{$this->myMoney}",
                ],
                [
                    'type' => 'input',
                    'text' => '個数を入力',
                    'placeholder' => '個数を入力してください',
                    'default' => '',
                ],
                [
                    'type' => 'toggle',
                    'text' => "仮想ストレージ優先",
                ],
            ],
        ];
    }
}
