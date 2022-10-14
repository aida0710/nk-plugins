<?php

namespace lazyperson0710\ShopAPI\form\levelShop;

use lazyperson0710\ShopAPI\database\LevelShopAPI;
use lazyperson710\core\packet\SoundPacket;
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
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        if ($data[1] === '' || !$this->isInteger($data[1]) || (int)floor($data[1]) <= 0) {
            $player->sendMessage('§bLevelShop §7>> §c1以上の整数を入力してください');
            SoundPacket::Send($player, 'dig.chain');
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
        $inventoryItemCount = $this->countItem($player, $this->item);
        if ($data[2] === true && $this->storage !== 0) {
            if ($count <= $this->storage) {
                $resultSalePrice = number_format($this->buyItemFromStackStorage($player, $this->item, $count));
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $count . "個アイテムが売却され、所持金が{$resultSalePrice}円増えました");
                SoundPacket::Send($player, 'break.amethyst_block');
                return;
            }
            $storageItemCount = $count - $this->storage;
            if ($storageItemCount <= $inventoryItemCount) {
                $storageResultSalePrice = $this->buyItemFromStackStorage($player, $this->item, $this->storage);
                $inventoryResultSalePrice = $this->buyItemFromInventory($player, $this->item, $storageItemCount);
                $resultSalePrice = number_format($storageResultSalePrice + $inventoryResultSalePrice);
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . number_format($this->storage) . "個とインベントリから" . number_format($storageItemCount) . "個、計" . number_format($this->storage + $storageItemCount) . "アイテムが売却され、所持金が{$resultSalePrice}円増えました");
                SoundPacket::Send($player, 'break.amethyst_block');
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        if (!$player->getInventory()->contains($this->item)) {
            $storageItemCount = $count - $inventoryItemCount;
            if ($storageItemCount <= $this->storage) {
                $storageResult = $this->buyItemFromStackStorage($player, $this->item, $storageItemCount); //$this->price * $storageItemCount;
                if ($inventoryItemCount === 0) {
                    $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . number_format($storageItemCount) . "個アイテムが売却され、所持金が " . number_format($storageResult) . "円増えました");
                    SoundPacket::Send($player, 'break.amethyst_block');
                    return;
                }
                $inventoryResult = $this->buyItemFromInventory($player, $this->item, $inventoryItemCount);
                $result = $inventoryResult + $storageResult;
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . number_format($storageItemCount) . "個とインベントリから" . number_format($inventoryItemCount) . "個、計" . number_format($storageItemCount + $inventoryItemCount) . "アイテムが売却され、所持金が" . number_format($result) . "円増えました");
                SoundPacket::Send($player, 'break.amethyst_block');
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        $this->buyItemFromInventory($player, $this->item, $count);
        $result = $this->price * $count;
        $player->sendMessage("§bLevelShop §7>> §aアイテムが" . number_format($count) . "個売却され、所持金が" . number_format($result) . "円増えました");
        SoundPacket::Send($player, 'break.amethyst_block');
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
                    'text' => "売却するアイテム/" . LevelShopAPI::getInstance()->getItemName($this->item->getId(), $this->item->getMeta()) . "\n1つあたりの値段/" . number_format($this->price) . "\n仮想ストレージにある量/" . number_format($this->storage) . "\nインベントリにある数/" . number_format($this->count) . "\n現在の所持金/" . number_format($this->myMoney),
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
