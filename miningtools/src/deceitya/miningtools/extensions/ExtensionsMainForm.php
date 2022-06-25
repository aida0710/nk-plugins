<?php

namespace deceitya\miningtools\extensions;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\element\SendFormButton;
use deceitya\miningtools\extensions\effect\EffectSelectForm;
use deceitya\miningtools\extensions\enchant\EnchantFunctionSelectForm;
use deceitya\miningtools\extensions\range\RangeConfirmForm;
use pocketmine\player\Player;

class ExtensionsMainForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText("強化したい機能を選択してください")
            ->addElements(
                new SendFormButton(new RangeConfirmForm($player), "採掘範囲拡張\n最大9x9まで拡張できます"),
                new SendFormButton(new EnchantFunctionSelectForm($player), "拡張エンチャント\nオーバーエンチャントが可能"),
                new SendFormButton(new EffectSelectForm(), "拡張エフェクト効果\n手に持った時にエフェクトが付与されるように")
            );
    }
}
