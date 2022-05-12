<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class FlyForm extends CustomForm {

    private Input $count;
    private Toggle $infinity;

    public function __construct() {
        $this->count = new Input(
            "売却したい土地の名前を入力してください\n自分の持っている土地は/land whoseか/land hereから調べることが可能です",
            "例: 1",
        );
        $this->infinity = new Toggle("無制限にする");
        $this
            ->setTitle("Fly Mode")
            ->addElements(
                new Label("フライ継続中は落下ダメージが消えます\n無制限にした場合秒数は無視されます"),
                $this->count,
                $this->infinity,
            );
    }

    public function handleClosed(Player $player): void {
        $player->sendForm(new LandForm($player));
        return;
    }

    public function handleSubmit(Player $player): void {
        if ($this->infinity->getValue() === true) {
            $this->FlyTaskExecution($player, true);
            return;
        }
        if ($this->count->getValue() === "") {
            $player->sendMessage("§bFlyMode §7>> §c持続時間(分)を入力してください");
            return;
        } elseif (!preg_match('/^[0-9]+$/', $this->count->getValue())) {
            $player->sendMessage("§bFlyMode §7>> §c持続時間は数字で入力してください");
            return;
        }
        $this->FlyTaskExecution($player, $this->infinity->getValue(), $this->count->getValue());
    }

    private function FlyTaskExecution(Player $player, bool $infinity, ?int $time = null) {
        //$infinityがtrueの場合は無制限、falseは有限のため$timeの分数を代わりに使用
    }
}