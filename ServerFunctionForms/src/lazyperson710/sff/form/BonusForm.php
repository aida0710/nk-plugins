<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\form\element\CommandDispatchButton;
use pocketmine\player\Player;

class BonusForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Login Bonus")
            ->setText("使用したい機能を選択してください")
            ->addElements(
                new CommandDispatchButton("ログインボーナスをアイテムと交換", "bonusconvert", null),
                new CommandDispatchButton("保留しているログインボーナスを回収", "bonuscollect", null),
            );
    }
}