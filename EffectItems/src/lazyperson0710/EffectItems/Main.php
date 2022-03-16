<?php

namespace lazyperson0710\EffectItems;

use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\Pickaxe;
use pocketmine\item\ToolTier;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new DamageEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new InteractEventListener(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ItemsScheduler, 20);
        //$tools = VanillaItems::DIAMOND_PICKAXE();
        //$tools->setCustomName("古びたつるはし");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("溶鉱炉");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("アルケミピッケル");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("鉱石より愛を込めて");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("ハイドロゲル");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("疾風のつるはし");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("溶岩泳者の友");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("暗夜行山");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("満福つるはし");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("重いつるはし");
        //CreativeInventory::getInstance()->add($tools);
        //$tools->setCustomName("窓一郎の炯眼");
        //CreativeInventory::getInstance()->add($tools);
        //$tools = VanillaItems::WOODEN_PICKAXE();
        //$tools->setCustomName("おもちゃのつるはし");
        //CreativeInventory::getInstance()->add($tools);//こいつらをオリジナルアイテムにする
        //todo {クアドラックス}一回の使用で手に入る原木が4つになる
        //todo {あっくす}斧...のおもちゃ。使っても木は壊せない
        //todo {火打石の斧}鉱石が焼かれた状態で採れるが、確率で火炎ダメージを受ける
        //todo {ふるびたシャベル}確率で壊れる
        //todo {地球より愛を込めて}掘っていると確率で鉱石がもらえる
        //todo {財宝発見!}確率でお金が手に入る 金額はランダム
        //todo {ポイズンシャベル}使うと確率で毒と空腹になる
        //todo {豊作シャベル}これで収穫するととれる作物が倍に増える
        //todo {豊作のクワ}これで収穫すると作物が通常の2倍手に入る
        //todo {凶歉のクワ}これで収穫しても何も手に入らない
        //todo {ブレイズのクワ}これで収穫するとかまどで調理された状態で収穫できる
        //todo {いちごの愛}これで作物をタップすると成長させることができる

    }
}
