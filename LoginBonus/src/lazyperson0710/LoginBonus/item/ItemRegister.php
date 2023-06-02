<?php

declare(strict_types = 0);

namespace lazyperson0710\LoginBonus\item;

use Error;
use lazyperson710\core\Main;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\utils\SingletonTrait;
use function count;

class ItemRegister {

    use SingletonTrait;

    private array $items;

    public function init() : void {
        //todo 交換するItemを追加してください
        #cost - 1
        $this->itemRegister(VanillaItems::APPLE(), 5, 1, 'りんご', [], 'ただのりんご', [], [], []);
        $this->itemRegister(VanillaItems::BAKED_POTATO(), 32, 1, 'ベイクドポテト', [], '主食にもってこいのじゃがいも！', [], [], []);
        $this->itemRegister(ItemFactory::getInstance()->get(Main::ITEM_COMMAND_BLOCK), 1, 1, 'コマンド記憶装置', ["使用方法\n\nスニークしないでタップすると指定されたコマンドを実行\nスニークしながらタップで実行するコマンドを指定"], '設定したコマンドを即座に実行可能に', [], [], ['CommandStorage']);
        #cost - 2
        $this->itemRegister(VanillaItems::IRON_BOOTS(), 1, 3, '落下ダメージ完全防御ブーツ', ['着用していると落下ダメージから自分を守ってくれる'], '落下ダメージ無効化鉄製ブーツ', [], [], ['DefensiveStone']);
        #cost - 3
        $this->itemRegister(VanillaItems::BREAD(), 32, 3, 'パン', ['パンはパンでも食べたいパン'], 'パンはパンでも食べたいパン', [], [], []);
        $this->itemRegister(VanillaItems::STEAK(), 32, 3, '焼き牛肉', ['牛肉は焼いたほうが美味しい'], '牛肉は焼いたほうが美味しい', [], [], []);
        $this->itemRegister(VanillaItems::EXPERIENCE_BOTTLE(), 64, 3, '魔剤', [], 'ただの経験値瓶', [], [], []);
        #cost - 5
        $this->itemRegister(VanillaItems::DIAMOND_BOOTS(), 1, 5, '落下ダメージ完全防御ブーツ', ['着用していると落下ダメージから自分を守ってくれる'], '落下ダメージ無効化§bダイヤモンド§r製ブーツ', [], [], ['DefensiveStone']);
        #cost - 8
        $this->itemRegister(VanillaItems::DIAMOND_PICKAXE(), 1, 8, '§a[Gacha]DiamondMiningPickaxe', ["通常仕様の特殊範囲破壊ピッケル\n\nログインボーナスから交換"], 'ダイアモンド範囲破壊つるはし/mtと同じアイテム', [VanillaEnchantments::SILK_TOUCH(), VanillaEnchantments::PUNCH(), VanillaEnchantments::UNBREAKING()], [1, 1, 5], ['MiningTools_3']);
        $this->itemRegister(VanillaItems::DIAMOND_PICKAXE(), 1, 8, '<神器>ダイヤのつるはし[効率lv.8]', ['過去実装された当時は強かったもの'], '当時は強かったもの', [VanillaEnchantments::EFFICIENCY()], [8], []);
        #cost - 12
        $this->itemRegister(VanillaItems::GOLD_INGOT(), 1, 12, '道具名チェンジャー', ["道具、装備の名前を変更することが可能です\n名前を変更したいアイテムを持った状態で/itemsコマンドを実行してください"], '道具の名前を変更できるアイテム', [VanillaEnchantments::PROJECTILE_PROTECTION()], [5], ['ItemNameChangeIngot']);
    }

    public function getItems() : array {
        return $this->items;
    }

    private function itemRegister(Item $item, int $quantity, int $cost, string $customName, array $lore, ?string $formExplanation, array $enchants, array $level, array $nbt) : void {
        if (count($enchants) !== count($level)) {
            throw new Error('LoginBonus : アイテム登録時にエンチャントとレベルの数が一致していない為プラグインを停止します');
        }
        $this->items[] = (new LoginBonusItemInfo($item, $quantity, $cost, $customName, $lore, $formExplanation, $enchants, $level, $nbt));
    }

}
