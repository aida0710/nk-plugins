<?php

namespace deceitya\miningtools\extensions\range;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\element\SendFormButton;
use deceitya\miningtools\extensions\effect\EffectSelectForm;
use pocketmine\player\Player;

class ExtensionsMainForm extends SimpleForm {

    public function __construct(Player $player) {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('4mining') !== null) {
            //todo これを購入時に一応全部やった方がいいかも
            $this
                ->setTitle("test")
                ->setText("一度ブロックを採掘してつるはしを変換してください\n変換が完了するまでこの機能は利用できません");
            return;
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText("強化したい機能を選択してください")
            ->addElements(
                new SendFormButton(new RangeConfirmForm($player), "採掘範囲拡張\n最大9x9まで拡張できます"),
                new SendFormButton(new EffectSelectForm($player), "拡張エンチャント\nオーバーエンチャントが可能"),
                new SendFormButton(new EffectSelectForm($player), "拡張エフェクト効果\n手に持った時にエフェクトが付与されるように")
            );
    }
}
