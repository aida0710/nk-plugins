<?php

namespace lazyperson0710\LoginBonus\item;

use lazyperson0710\Gacha\Main;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\utils\SingletonTrait;

class ItemRegister {

    use SingletonTrait;

    private array $items;

    /**
     * @return void
     */
    public function init(): void {
        ##Item交換
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "赤いリンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 1, "いリゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "いリンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "ンゴ", [], "赤いリンゴじゃないです", [], [], []);
    }

    private function itemRegister(Item $item, int $quantity, int $cost, string $customName, array $lore, ?string $formExplanation, array $enchants, array $level, array $nbt): void {
        if (count($enchants) !== count($level)) {
            Main::getInstance()->getLogger()->critical("Gacha : アイテム登録時にエンチャントとレベルの数が一致していない為プラグインを停止します");
            Main::getInstance()->getServer()->getPluginManager()->disablePlugin(Main::getInstance());
            return;
        }
        $this->items[] = (new LoginBonusItemInfo($item, $quantity, $cost, $customName, $lore, $formExplanation, $enchants, $level, $nbt));
    }

    /**
     * @return array
     */
    public function getItems(): array {
        return $this->items;
    }

}