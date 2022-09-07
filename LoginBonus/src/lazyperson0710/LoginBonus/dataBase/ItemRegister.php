<?php

namespace lazyperson0710\LoginBonus\dataBase;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\utils\SingletonTrait;

class ItemRegister {
    private static ItemRegister $instance;


    private array $items;
    /**ticketは別枠で表示
     * [ticketと交換]
     * 下にアイテムのリスト表示
     * stone
     * wood
     * etc...
     */
    private array $tickets;
    private array $cost;

    /**
     * アイテムの検索方法的にアイテム、コスト、配布数が同じアイテムは二つ以上追加不可
     * @return void
     */
    public function init(): void {
        ##Item交換
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "赤いリンゴ");
        ##Ticket交換
        $this->ticketRegister(1, 1);
    }

    private function itemRegister(Item $item, int $quantity, int $cost, ?string $customName = null, ?array $lore = [], ?string $formExplanation = null): void {
        $items[] = [$item, $quantity, $cost, $customName, $lore, $formExplanation];
        ///$item[$item->getVanillaName()][$quantity][$cost] =

    }

    private function ticketRegister(int $ticketQuantity, int $cost): void {
    }

    /**
     * @return array
     */
    public function getItems(): array {
        return $this->items;
    }

    public static function getInstance(): ItemRegister {
        if (!isset(self::$instance)) {
            self::$instance = new ItemRegister();
        }
        return self::$instance;
    }

}