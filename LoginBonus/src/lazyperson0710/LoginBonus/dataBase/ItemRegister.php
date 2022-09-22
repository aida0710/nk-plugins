<?php

namespace lazyperson0710\LoginBonus\dataBase;

use lazyperson0710\Gacha\Main;
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
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "赤いリンゴ", [], null, [],);
        $this->itemRegister(VanillaItems::APPLE(), 40, 1, "いリゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "いリンゴ");
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "ンゴ", null, "赤いリンゴじゃないです");
    }

    //エンチャントとnbtタグを追加できるように
    private function itemRegister(Item $item, int $quantity, int $cost, string $customName, array $lore, ?string $formExplanation, array $enchants, array $level, array $nbt): void {
        if (count($enchants) !== count($level)) {
            Main::getInstance()->getLogger()->critical("Gacha : アイテム登録時にエンチャントとレベルの数が一致していない為プラグインを停止します");
            Main::getInstance()->getServer()->getPluginManager()->disablePlugin(Main::getInstance());
            return;
        }
        $this->items[] = [
            "item" => $item,
            "quantity" => $quantity,
            "cost" => $cost,
            "customName" => $customName,
            "lore" => $lore,
            "formExplanation" => $formExplanation,
            "enchants" => $enchants,
            "level" => $level,
            "nbt" => $nbt,
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