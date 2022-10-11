<?php

namespace lazyperson0710\LoginBonus\item;

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
        //todo 交換するItemを追加してください
        ##Cost - 1
        $this->itemRegister(VanillaItems::APPLE(), 5, 1, "りんご", [], "ただのりんご", [], [], []);
        $this->itemRegister(VanillaItems::BAKED_POTATO(), 32, 1, "ベイクドポテト", [], "主食にもってこいのじゃがいも！", [], [], []);
        //todo コマンド記憶装置の判定方法を変える必要があります
        // 現在はid判定だとは思いますがそれに付随してnbt判定をメインで行いたい
        // EffectItemsの方に動作クラスが存在すると思いますのでLoginBonusのeventListenerにも移動させたい
        $this->itemRegister(ItemFactory::getInstance()->get(\lazyperson710\core\Main::ITEM_COMMAND_BLOCK), 1, 1, "コマンド記憶装置", ["使用方法\n\nスニークしないでタップすると指定されたコマンドを実行\nスニークしながらタップで実行するコマンドを指定"], "設定したコマンドを即座に実行可能に", [], [], []);
        ##Cost - 3
        //食べ物とか
        $this->itemRegister(VanillaItems::DIAMOND_PICKAXE(), 1, 8, "<神器>ダイヤのつるはし[効率lv.8]", ["過去実装された当時に強かったもの"], "当時は強かったもの", [VanillaEnchantments::EFFICIENCY()], [8], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤いゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "赤リンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 40, 3, "いリンゴ", [], null, [], [], []);
        $this->itemRegister(VanillaItems::APPLE(), 30, 1, "ンゴ", [], "赤いリンゴじゃないです", [], [], []);
    }

    private function itemRegister(Item $item, int $quantity, int $cost, string $customName, array $lore, ?string $formExplanation, array $enchants, array $level, array $nbt): void {
        if (count($enchants) !== count($level)) {
            throw new \Error("LoginBonus : アイテム登録時にエンチャントとレベルの数が一致していない為プラグインを停止します");
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