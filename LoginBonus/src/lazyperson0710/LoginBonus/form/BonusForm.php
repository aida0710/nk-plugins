<?php

namespace lazyperson0710\LoginBonus\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\LoginBonus\form\acceptance\BonusViewForm;
use lazyperson0710\LoginBonus\form\convert\ItemSelectForm;
use lazyperson710\sff\form\element\CommandDispatchButton;
use lazyperson710\sff\form\element\SendFormButton;
use pocketmine\player\Player;

class BonusForm extends SimpleForm {

    public function __construct(Player $player, ?string $message = null) {
        $this
            ->setTitle("Login Bonus")
            ->setText("使用したい機能を選択してください" . $message)
            ->addElements(
                new SendFormButton(new ItemSelectForm(), "ログインボーナスをアイテムと交換"),
                new SendFormButton(new BonusViewForm($player), "保留しているログインボーナスを回収"),
            );
    }
}