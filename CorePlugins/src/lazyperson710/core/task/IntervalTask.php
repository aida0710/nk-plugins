<?php

namespace lazyperson710\core\task;

use lazyperson710\core\Main;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class IntervalTask {

    public static array $WaitingTaskList = [];

    public static function onRun(Player $player, string $intervalName, int $tick): void {
        self::$WaitingTaskList[$player->getName()][$intervalName] = true;
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
            function () use ($player, $intervalName): void {
                self::unset($player, $intervalName);
            }
        ), $tick);
    }

    public static function unset(Player $player, string $intervalName): void {
        unset(self::$WaitingTaskList[$player->getName()][$intervalName]);
    }

    public static function check(Player $player, string $intervalName): bool {
        return isset(self::$WaitingTaskList[$player->getName()][$intervalName]);
    }

}