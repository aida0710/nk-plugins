<?php

declare(strict_types = 0);

namespace Deceitya\SBI\mode;

use pocketmine\player\Player;

class NoMode implements Mode {

    public function getId() : int {
        return self::NO;
    }

    public function getLines(Player $player) : ?array {
        return null;
    }

    public function getName() : string {
        return '削除';
    }
}
