<?php

namespace deceitya\levelShop\form;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use ree_jp\stackStorage\api\StackStorageAPI;

class SaleForm implements Form {

    private $item;
    private $price;
    private $count;
    private $mymoney;
    private $strage;

    public function __construct(Item $item, int $price, int $count, int $mymoney, int $strage) {
        $this->item = $item;
        $this->price = $price;
        $this->count = $count;
        $this->mymoney = $mymoney;
        $this->strage = $strage;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || $data[1] === null) {
            $player->sendMessage('§bLevelShop §7>> §aアイテムの売却がキャンセルされました');
            return;
        }
        if ($data[1] === '' || !$this->isInteger($data[1]) || (int)floor($data[1]) <= 0) {
            $player->sendMessage('§bLevelShop §7>> §c1以上の整数を入力してください');
            return;
        }
        $count = (int)floor($data[1]);
        $this->item->setCount($count);
        StackStorageAPI::$instance->getCount($player->getXuid(), $this->item, function ($count) use ($player, $data): void {
            $this->strage = $count;
            $this->transaction($player, $data);
        }, function () use ($player, $data): void {
            $this->strage = 0;
            $this->transaction($player, $data);
        });
    }

    private function isInteger($input): bool {
        return (ctype_digit(strval($input)));
    }

    public function transaction(Player $player, array $data): void {
        $count = (int)floor($data[1]);
        $this->item->setCount($count);
        $inventory = $this->countItem($player, $this->item);
        if ($data[2] === true && $this->strage !== 0) {
            if ($count <= $this->strage) {
                $stackstorage_totalprice = $this->buyItemFromStackStorage($player, $this->item, $count);
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $count . "個アイテムが売却され、所持金が{$stackstorage_totalprice}円増えました");
                return;
            }
            $stackstorage_count = $count - $this->strage;
            if ($stackstorage_count <= $inventory) {
                //stackstorage
                $stackstorage_totalprice = $this->buyItemFromStackStorage($player, $this->item, $this->strage);
                //inventory
                $inventory_totalprice = $this->buyItemFromInventory($player, $this->item, $stackstorage_count);
                $totalprice = $inventory_totalprice + $stackstorage_totalprice;
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $this->strage . "個とインベントリから" . $stackstorage_count . "個、計" . ($this->strage + $stackstorage_count) . "アイテムが売却され、所持金が{$totalprice}円増えました");
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            return;
        }
        if (!$player->getInventory()->contains($this->item)) {
            $stackstorage_count = $count - $inventory;
            if ($stackstorage_count <= $this->strage) {
                //stackstorage
                $stackstorage_totalprice = $this->buyItemFromStackStorage($player, $this->item, $stackstorage_count); //$this->price * $stackstorage_count;
                if ($inventory === 0) {
                    $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $stackstorage_count . "個アイテムが売却され、所持金が{$stackstorage_totalprice}円増えました");
                    return;
                }
                //inventory
                $inventory_totalprice = $this->buyItemFromInventory($player, $this->item, $inventory);
                $totalprice = $inventory_totalprice + $stackstorage_totalprice;
                $player->sendMessage("§bLevelShop §7>> §a仮想ストレージから" . $stackstorage_count . "個とインベントリから" . $inventory . "個、計" . ($stackstorage_count + $inventory) . "アイテムが売却され、所持金が{$totalprice}円増えました");
                return;
            }
            $player->sendMessage('§bLevelShop §7>> §cアイテムがない、もしくは足りません');
            return;
        }
        $this->buyItemFromInventory($player, $this->item, $count);
        $totalprice = $this->price * $count;
        $player->sendMessage("§bLevelShop §7>> §aアイテムが" . $count . "個売却され、所持金が{$totalprice}円増えました");
    }

    public function countItem(Player $player, Item $targetitem): int {
        $inventory = 0;
        for ($i = 0; $i <= 35; $i++) {
            $item = $player->getInventory()->getItem($i);
            if ($targetitem->equals($item)) {
                $inventory += $item->getCount();
            }
        }
        return $inventory;
    }

    public function buyItemFromStackStorage(Player $player, Item $item, int $count): int {
        $item = (clone $item)->setCount($count);
        //		if($count > $this->strage){
        //
        //		}
        $stackstorage_totalprice = $this->price * $count;
        StackStorageAPI::$instance->remove($player->getXuid(), $item);
        EconomyAPI::getInstance()->addMoney($player, $stackstorage_totalprice);
        return $stackstorage_totalprice;
    }

    public function buyItemFromInventory(Player $player, Item $item, int $count): int {
        $item = (clone $item)->setCount($count);
        //		if(!$player->getInventory()->contains($item)){
        //			throw new \RuntimeException("");
        //		}
        $totalprice = $this->price * $count;
        $player->getInventory()->removeItem($item);
        EconomyAPI::getInstance()->addMoney($player, $this->price * $count);
        return $totalprice;
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'LevelShop',
            'content' => [
                [
                    'type' => 'label',
                    'text' => "売却するアイテム/{$this->item->getName()}\n1つあたりの値段/{$this->price}\n仮想ストレージにある量/{$this->strage}\nインベントリにある数/{$this->count}\n現在の所持金/{$this->mymoney}"
                ],
                [
                    'type' => 'input',
                    'text' => '個数を入力',
                    'placeholder' => '個数を入力してください。',
                    'default' => ''
                ],
                [
                    'type' => 'toggle',
                    'text' => "仮想ストレージ優先"
                ]
            ]
        ];
    }
}
