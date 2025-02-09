<?php

declare(strict_types = 0);

namespace Deceitya\SBI\mode;

use pocketmine\player\Player;

interface Mode {

    public const NORMAL = 0;
    public const SIMPLE = 1;
    public const ITEM = 2;
    public const TIME = 3;
    public const NO = 4;

    public function getId() : int;

    public function getName() : string;

    /**
     * @return string[]|null
     */
    public function getLines(Player $player) : ?array;
}
