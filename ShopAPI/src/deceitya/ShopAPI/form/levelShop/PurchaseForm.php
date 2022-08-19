<?php

namespace deceitya\ShopAPI\form\levelShop;

use deceitya\ShopAPI\database\LevelShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use ree_jp\stackStorage\api\StackStorageAPI;

class PurchaseForm implements Form {

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
        if ($data === null || $data[1] === null) {
            $player->sendMessage("§bLevelShop §7>> §a{$this->api->getItemName($this->item->getId(), $this->item->getMeta())}の購入をキャンセルしました");
            return;
        }
        if ($data[1] === '' || !$this->isInteger($data[1]) || (int)floor($data[1]) <= 0) {
            $player->sendMessage('§bLevelShop §7>> §c1以上の整数を入力してください');
            return;
        }
        $count = (int)floor($data[1]);
        $totalPrice = $this->price * $count; //1 = 変数名、衝突対策...
        $result = $totalPrice - $this->myMoney;
        if ($this->myMoney < $totalPrice) {
            $player->sendMessage("§bLevelShop §7>> §cお金が" . number_format($result) . "円足りませんでした。合計必要金額:" . number_format($totalPrice) . "円");
            return;
        }
        $this->item->setCount($count);
        if ($data[2] === true) {
            EconomyAPI::getInstance()->reduceMoney($player, $this->price * $count);
            StackStorageAPI::$instance->add($player->getXuid(), $this->item);
            $totalPrice = $this->price * $count;
            $player->sendMessage("§bLevelShop §7>> §a{$this->api->getItemName($this->item->getId(), $this->item->getMeta())}を" . number_format($count) . "個購入し、仮想ストレージに転送しました。使用金額 : " . number_format($totalPrice) . "円");
            return;
        }
        if (!$player->getInventory()->canAddItem($this->item)) {
            $player->sendMessage('§bLevelShop §7>> §cインベントリに空きはありません');
            return;
        }
        $player->getInventory()->addItem($this->item);
        EconomyAPI::getInstance()->reduceMoney($player, $this->price * $count);
        $totalPrice = $this->price * $count;
        $player->sendMessage("§bLevelShop §7>> §a{$this->api->getItemName($this->item->getId(), $this->item->getMeta())}を" . number_format($count) . "個購入しました。使用金額 : " . number_format($totalPrice) . "円");
    }

    private function isInteger($input): bool {
        return (ctype_digit(strval($input)));
    }

    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'LevelShop',
            'content' => [
                [
                    'type' => 'label',
                    'text' => "購入するアイテム/{$this->api->getItemName($this->item->getId(), $this->item->getMeta())}\n1つあたりの値段/" . number_format($this->price) . "\n仮想ストレージにある量/" . number_format($this->storage) . "\nインベントリにある数/" . number_format($this->count) . "\n現在の所持金/" . number_format($this->myMoney),
                ],
                [
                    'type' => 'input',
                    'text' => '個数を入力',
                    'placeholder' => '個数を入力してください',
                    'default' => '',
                ],
                [
                    'type' => 'toggle',
                    'text' => "仮想ストレージに転送",
                ],
            ],
        ];
    }
}
