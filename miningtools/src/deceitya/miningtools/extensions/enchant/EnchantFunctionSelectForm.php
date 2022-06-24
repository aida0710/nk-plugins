<?php

namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\element\SendFormButton;
use pocketmine\player\Player;

class EnchantFunctionSelectForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Enchant Form")
            ->setText("付与したい拡張機能を選択して下さい")
            ->addElements(
                new SendFormButton(new EnchantConfirmForm($player), "耐久エンチャントを強化"),
                new SendFormButton(new EnchantConfirmForm($player), "シルクタッチを幸運に変化"),
            );
    }

}