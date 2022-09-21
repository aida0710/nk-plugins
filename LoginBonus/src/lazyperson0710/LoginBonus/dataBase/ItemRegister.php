<?php

namespace lazyperson0710\LoginBonus\dataBase;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class ItemRegister {

    private static ItemRegister $instance;

    private array $items;

    /**
     * @return void
     */
    public function init(): void {
        ##Item交換
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "赤いリンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 1, "いリゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "いリンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "ンゴ", null, "赤いリンゴじゃないです");
    }

    //エンチャントとnbtタグを追加できるように
    private function itemRegister(Item $item, int $quantity, int $cost, string $customName, ?array $lore = [], ?string $formExplanation = null): void {
        $this->items[] = [
            "item" => $item,
            "quantity" => $quantity,
            "cost" => $cost,
            "customName" => $customName,
            "lore" => $lore,
            "formExplanation" => $formExplanation,
        ];
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