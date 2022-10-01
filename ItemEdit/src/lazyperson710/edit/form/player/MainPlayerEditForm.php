<?php

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\edit\form\element\SendFormButton;
use pocketmine\player\Player;

class MainPlayerEditForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Player Edit")
            ->setText("選択してください")
            ->addElements(
                new SendFormButton(new AddExpPlayer($player), "経験値レベルを編集"),
            //todo MiningLevelを編集
            //todo インベントリを開く
            //todo エフェクト付与
            //todo 設定の強制変更
            );
    }

}
