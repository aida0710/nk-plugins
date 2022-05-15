<?php

namespace lazyperson710\core\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class FlyCheckTask extends Task {

    static array $flyTask = [];

    private function Levy() {
        //$infinityがtrueの場合は無制限、falseは有限のため$timeの分数を代わりに使用
    }

    public function onRun(): void {
        /*FlyCheckTask::$flyTask = [
            $player->getName() => [
                "Mode" => "limited",
                "TimeLeft" => $timeLeft,
            ]
        ];*/
        if (empty($fly)) {
            return;
        }
        foreach (FlyCheckTask::$flyTask as $playerName => $list) {
            if (is_null(Server::getInstance()->getPlayerExact($playerName))) {
                unset(FlyCheckTask::$flyTask[$playerName]);
                var_dump("{$playerName}が存在しない為データを削除");
            }
            $player = Server::getInstance()->getPlayerExact($playerName);
            $time = FlyCheckTask::$flyTask[$list];
            if (in_array("unlimited", $list)) {
                $CurrentTime = FlyCheckTask::$flyTask[$list["Count"]] = $time++;
                if ($CurrentTime === 60) {
                    FlyCheckTask::$flyTask[$list["Count"]] = 0;
                    $player->sendTip("お金を1500円消費しました");
                }
            }
            FlyCheckTask::$flyTask[$list["Count"]] = $time - 1;
            if ($time % 60 != 0) {
                $player->sendTip("お金を1500円消費しました");
            }
            switch (FlyCheckTask::$flyTask[$list["Count"]]) {
                case 30:
                    $player->sendMessage("残り時間が30秒になりました");
                    break;
                case 15:
                    $player->sendMessage("残り時間が15秒になりました");
                    break;
                case 10:
                    $player->sendMessage("残り時間が10秒になりました");
                    break;
                case 5:
                    $player->sendMessage("残り時間が5秒になりました");
                    break;
                case 0:
                    $player->sendMessage("flyModeが終了しました");
                    break;
            }
        }
    }
}