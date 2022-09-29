<?php

namespace lazyperson0710\LoginBonus\item;

use lazyperson0710\Gacha\Main;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
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
        $this->itemRegister(VanillaItems::APPLE(), 5, 1, "りんご", [], "ただのりんご", [], [], []);
        $this->itemRegister(VanillaItems::BAKED_POTATO(), 32, 1, "ベイクドポテト", [], "主食にもってこいのじゃがいも！", [], [], []);
        $this->itemRegister(ItemFactory::getInstance()->get(\lazyperson710\core\Main::ITEM_COMMAND_BLOCK), 1, 1, "コマンド記憶装置", ["使用方法\n\nスニークしないでタップすると指定されたコマンドを実行\nスニークしながらタップで実行するコマンドを指定"], "設定したコマンドを即座に実行可能に", [], [], []);
        $this->itemRegister(VanillaItems::DIAMOND_PICKAXE(), 1, 8, "<神器>ダイヤのつるはし[効率lv.8]", ["過去実装された当時に強かったもの"], "当時は強かったもの", [VanillaEnchantments::EFFICIENCY()], [], []);
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