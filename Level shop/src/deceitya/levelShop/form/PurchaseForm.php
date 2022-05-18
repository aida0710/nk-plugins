<?php

namespace deceitya\levelShop\form;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\player\Player;
use ree_jp\stackStorage\api\StackStorageAPI;

class PurchaseForm implements Form {

    private Item $item;
    private int $price;
    private int $count;
    private int $mymoney;
    private int $strage;

    public function __construct(Item $item, int $price, int $count, int $mymoney, int $strage) {
        $this->item = $item;
        $this->price = $price;
        $this->count = $count;
        $this->mymoney = $mymoney;
        $this->strage = $strage;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null || $data[1] === null) {
            $player->sendMessage('§bLevelShop §7>> §a購入をキャンセルしました');
            return;
        }
        if ($data[1] === '' || !$this->isInteger($data[1]) || (int)floor($data[1]) <= 0) {
            $player->sendMessage('§bLevelShop §7>> §c1以上の整数を入力してください');
            return;
        }
        $count = (int)floor($data[1]);
        $totalprice1 = $this->price * $count; //1 = 変数名、衝突対策...
        $goukei = $totalprice1 - $this->mymoney;
        if ($this->mymoney < $totalprice1) {
            $player->sendMessage("§bLevelShop §7>> §cお金が{$goukei}円足りませんでした。合計必要金額:{$totalprice1}円");
            return;
        }
        $this->item->setCount($count);
        if ($data[2] === true) {
            EconomyAPI::getInstance()->reduceMoney($player, $this->price * $count);
            StackStorageAPI::$instance->add($player->getXuid(), $this->item);
            $totalprice1 = $this->price * $count;
            $player->sendMessage("§bLevelShop §7>> §a{$this->item->getName()}を{$count}個購入し、仮想ストレージに転送しました。使用金額 : {$totalprice1}");
            return;
        }
        if (!$player->getInventory()->canAddItem($this->item)) {
            $player->sendMessage('§bLevelShop §7>> §cインベントリに空きはありません');
            return;
        }
        $player->getInventory()->addItem($this->item);
        EconomyAPI::getInstance()->reduceMoney($player, $this->price * $count);
        $totalprice1 = $this->price * $count;
        $player->sendMessage("§bLevelShop §7>> §a{$this->item->getName()}を{$count}個購入しました。使用金額 : {$totalprice1}");
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
                    'text' => "購入するアイテム/{$this->item->getName()}\n1つあたりの値段/{$this->price}\n仮想ストレージにある量/{$this->strage}\nインベントリにある数/{$this->count}\n現在の所持金/{$this->mymoney}"
                ],
                [
                    'type' => 'input',
                    'text' => '個数を入力',
                    'placeholder' => '個数を入力してください。',
                    'default' => ''
                ],
                [
                    'type' => 'toggle',
                    'text' => "仮想ストレージに転送"
                ]
            ]
        ];
    }
}
