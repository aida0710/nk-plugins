<?php

namespace Deceitya\Flytra\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\Flytra\Main;
use Deceitya\Flytra\task\FlyCheckTask;
use pocketmine\player\Player;

class FlyForm extends CustomForm {

    private Input $count;
    private Toggle $infinity;

    public function __construct(Player $player) {
        if (array_key_exists($player->getName(), FlyCheckTask::$flyTask)) {
            if (FlyCheckTask::$flyTask[$player->getName()]["Mode"] === "limited") {
                $minutes = FlyCheckTask::$flyTask[$player->getName()]["TimeLeft"] / 60;
                $minutes = floor($minutes);
                $seconds = FlyCheckTask::$flyTask[$player->getName()]["TimeLeft"] % 60;
                $this->addElement(new Label("\n残り時間 : {$minutes}分{$seconds}秒"));
            } else {
                $this->addElement(new Label("\n残り時間 : 無限"));
            }
            $this
                ->setTitle("Fly Mode")
                ->addElement(new Label("flyTaskを停止させたい場合は「送信」ボタンを押してください"));
        } else {
            $this->count = new Input(
                "分数を入力してください",
                "例: 1",
            );
            $this->infinity = new Toggle("無制限にする");
            $this
                ->setTitle("Fly Mode")
                ->addElements(
                    new Label("フライ継続中は落下ダメージが消えます\n無制限にした場合分数は無視されます\n\n毎分1500円づつ消費します\nまた、途中でやめたい場合は再度/flyと入力してください\n\n注意 : 鯖を退出した場合はflyTaskが自動的に停止されます"),
                    $this->count,
                    $this->infinity,
                );
        }
    }

    public function handleSubmit(Player $player): void {
        if (array_key_exists($player->getName(), FlyCheckTask::$flyTask)) {
            unset(FlyCheckTask::$flyTask[$player->getName()]);
            Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate());
            $player->sendMessage("§bFlyTask §7>> §aFlyTaskを停止させました");
            return;
        }
        if ($this->infinity->getValue() === true) {
            FlyCheckTask::$flyTask = [
                $player->getName() => [
                    "Mode" => "unlimited",
                    "TimeLeft" => null,
                    "Flag" => false,
                ],
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
        if ($this->count->getValue() <= 0) {
            $player->sendMessage("§bFlyTask §7>> §c持続時間は1分以上で入力してください");
            return;
        }
        $timeLeft = $this->count->getValue() * 60;
        FlyCheckTask::$flyTask = [
            $player->getName() => [
                "Mode" => "limited",
                "TimeLeft" => $timeLeft,
                "Flag" => false,
            ],
        ];
    }

}