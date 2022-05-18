<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\util;

use pocketmine\player\Player;

class InteractFlag {

    /** @var float[] */
    private static array $lastSignTouchTime = [];

    public static function get(Player $player): float {
        return self::$lastSignTouchTime[$player->getName()] ?? 0;
    }

    public static function update(Player $player) {
        self::$lastSignTouchTime[$player->getName()] = microtime(true);
    }

    public static function delete(Player $player) {
        unset(self::$lastSignTouchTime[$player->getName()]);
    }
}