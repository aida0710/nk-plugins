<?php

namespace Deceitya\SBI\mode;

use pocketmine\player\Player;

interface Mode {

    public const NORMAL = 0;
    public const SIMPLE = 1;
    public const ITEM = 2;
    public const NO = 3;

    public function getId(): int;

    public function getName(): string;

    /**
     * @param Player $player
     * @return string[]|null
     */
    public function getLines(Player $player): ?array;
}