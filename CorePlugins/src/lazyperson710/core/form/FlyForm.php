<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\task\FlyCheckTask;
use pocketmine\player\Player;

class FlyForm extends CustomForm {

    private Input $count;
    private Toggle $infinity;

    public function __construct(Player $player) {
        if (array_key_exists($player->getName(), FlyCheckTask::$flyTask)) {
            $this
                ->setTitle("Fly Mode")
                ->addElements(
                    new Label("flyTaskを停止させたい場合は「送信」ボタンを押してください"),
                );
        }
        $this->count = new Input(
            "分数を入力してください",
            "例: 1",
        );
        $this->infinity = new Toggle("無制限にする");
        $this
            ->setTitle("Fly Mode")
            ->addElements(
                new Label("フライ継続中は落下ダメージが消えます\n無制限にした場合分数は無視されます\n\n毎分1500円づつ消費します\nまた、途中でやめたい場合は再度/flyと入力してください"),
                $this->count,
                $this->infinity,
            );
    }

    public function handleSubmit(Player $player): void {
        if ($this->infinity->getValue() === true) {
            FlyCheckTask::$flyTask = [
                $player->getName() => [
                    "Mode" => "unlimited",//trueは有限60 * timeで出た数が記録される。60秒ごとにチェックを入れる
                    "TimeLeft" => null,
                ]
            ];
            return;
        }
        if ($this->count->getValue() === "") {
            $player->sendMessage("§bFlyMode §7>> §c持続時間(分)を入力してください");
            return;
        }
        if (is_numeric($this->count->getValue()) === false) {
            $player->sendMessage("§bFlyMode §7>> §c持続時間は数字で入力してください");
            return;
        }
        if ($this->count->getValue() >= 0) {
            $player->sendMessage("§bFlyMode §7>> §c持続時間は1分以上で入力してください");
            return;
        }
        $timeLeft = $this->count->getValue() * 60;
        FlyCheckTask::$flyTask = [
            $player->getName() => [
                "Mode" => "limited",
                "TimeLeft" => $timeLeft,
            ]
        ];
    }

}