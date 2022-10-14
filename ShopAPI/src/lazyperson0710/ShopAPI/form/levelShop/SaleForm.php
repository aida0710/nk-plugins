<?php

namespace lazyperson0710\ShopAPI\form\levelShop;

use lazyperson0710\ShopAPI\database\LevelShopAPI;
use lazyperson0710\ShopAPI\event\LevelShopClosingEvent;
use lazyperson0710\ShopAPI\object\LevelShopItem;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use ree_jp\stackStorage\api\StackStorageAPI;

class SaleForm implements Form {

    private LevelShopAPI $api;

    public function __construct(
        private LevelShopItem $item,
    ) {
        $this->api = LevelShopAPI::getInstance();
    }

    public function handleResponse(Player $player, $data): void {
        $api = LevelShopAPI::getInstance();
        if ($data === null || $data[1] === null) {
            $player->sendMessage("§bLevelShop §7>> §a{$this->api->getItemName($this->item->getItem()->getId(), $this->item->getItem()->getMeta())}の売却がキャンセルされました");
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        if ($data[1] === '' || !$this->isInteger($data[1]) || (int)floor($data[1]) <= 0) {
            $player->sendMessage('§bLevelShop §7>> §c1以上の整数を入力してください');
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        $count = (int)floor($data[1]);
        $this->item->getItem()->setCount($count);
        StackStorageAPI::$instance->getCount($player->getXuid(), $this->item->getItem(), function ($count) use ($player, $data): void {
            $this->item->setStorage($count);
            $this->transaction($player, $data);
        }, function () use ($player, $data): void {
            $this->item->setStorage(0);
            $this->transaction($player, $data);
        });
    }

    private function isInteger($input): bool {
        return (ctype_digit(strval($input)));
    }

    public function transaction(Player $player, array $data): void {
        $count = floor($data[1]);
        $this->item->getItem()->setCount($count);
        $inventoryItemCount = $this->countItem($player, $this->item->getItem());
        if ($data[2] === true && $this->item->getStorage() !== 0) {
            if ($count <= $this->item->getStorage()) {
                $resultSalePrice = number_format($this->buyItemFromStackStorage($player, $this->item->getItem(), $count));
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $count . "個アイテムが売却され、所持金が{$resultSalePrice}円増えました");
                SoundPacket::Send($player, 'break.amethyst_block');
                return;
            }
            $storageItemCount = $count - $this->item->getStorage();
            if ($storageItemCount <= $inventoryItemCount) {
                $storageResultSalePrice = $this->buyItemFromStackStorage($player, $this->item->getItem(), $this->item->getStorage());
                $inventoryResultSalePrice = $this->buyItemFromInventory($player, $this->item->getItem(), $storageItemCount);
                $resultSalePrice = number_format($storageResultSalePrice + $inventoryResultSalePrice);
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . number_format($this->item->getStorage()) . "個とインベントリから" . number_format($storageItemCount) . "個、計" . number_format($this->item->getStorage() + $storageItemCount) . "アイテムが売却され、所持金が{$resultSalePrice}円増えました");
                SoundPacket::Send($player, 'break.amethyst_block');
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        if (!$player->getInventory()->contains($this->item->getItem())) {
            $storageItemCount = $count - $inventoryItemCount;
            if ($storageItemCount <= $this->item->getStorage()) {
                $storageResult = $this->buyItemFromStackStorage($player, $this->item->getItem(), $storageItemCount); //$this->price * $storageItemCount;
                if ($inventoryItemCount === 0) {
                    $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . number_format($storageItemCount) . "個アイテムが売却され、所持金が " . number_format($storageResult) . "円増えました");
                    SoundPacket::Send($player, 'break.amethyst_block');
                    return;
                }
                $inventoryResult = $this->buyItemFromInventory($player, $this->item->getItem(), $inventoryItemCount);
                $result = $inventoryResult + $storageResult;
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . number_format($storageItemCount) . "個とインベントリから" . number_format($inventoryItemCount) . "個、計" . number_format($storageItemCount + $inventoryItemCount) . "アイテムが売却され、所持金が" . number_format($result) . "円増えました");
                SoundPacket::Send($player, 'break.amethyst_block');
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            SoundPacket::Send($player, 'dig.chain');
            return;
        }
        $this->buyItemFromInventory($player, $this->item->getItem(), $count);
        $result = $this->item->getPrice() * $count;
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
        $storageResult = $this->item->getPrice() * $count;
        StackStorageAPI::$instance->remove($player->getXuid(), $item);
        EconomyAPI::getInstance()->addMoney($player, $storageResult);
        return $storageResult;
    }

    public function buyItemFromInventory(Player $player, Item $item, int $count): int {
        $item = (clone $item)->setCount($count);
        $result = $this->item->getPrice() * $count;
        $player->getInventory()->removeItem($item);
        EconomyAPI::getInstance()->addMoney($player, $this->item->getPrice() * $count);
        return $result;
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'LevelShop',
            'content' => [
                [
                    'type' => 'label',
                    'text' => "売却するアイテム/" . LevelShopAPI::getInstance()->getItemName($this->item->getItem()->getId(), $this->item->getItem()->getMeta()) . "\n1つあたりの値段/" . number_format($this->item->getPrice()) . "\n仮想ストレージにある量/" . number_format($this->item->getStorage()) . "\nインベントリにある数/" . number_format($this->item->getCount()) . "\n現在の所持金/" . number_format($this->item->getMyMoney()),
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
