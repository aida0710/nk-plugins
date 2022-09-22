<?php

namespace lazyperson0710\LoginBonus\form\acceptance;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\event\JoinPlayerEvent;
use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson0710\LoginBonus\Main;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class BonusViewForm extends CustomForm {

    private Toggle $toggle;

    public function __construct(Player $player) {
        $this->toggle = new Toggle("ログインボーナスを受け取る", false);
        $this
            ->setTitle("ログインボーナス")
            ->addElements(
                new Label("現在保留しているログインボーナス数 : ". Main::getInstance()->lastBonusDateConfig->get($player->getName())),
                new Label("ログインボーナスを受け取りますか？\n受け取りたい場合は下にあるトグルをタップし「送信」ボタンを押してください"),
                $this->toggle,
            );
    }

    public function handleSubmit(Player $player): void {
        if ($this->toggle->getValue()) {
            JoinPlayerEvent::check($player);
        } else{
            SendForm::Send($player, new BonusForm($player, "\n§aログインボーナスを受け取りませんでした"));
        }
    }
}
