<?php

namespace bbo51dog\anticheat\model;

use bbo51dog\anticheat\chcker\BlockBreakChecker;
use bbo51dog\anticheat\chcker\Checker;
use pocketmine\player\Player;

class PlayerData {

    private Player $player;

    /** @var Checker[] */
    private array $checkers;

    /**
     * @param Player $player
     */
    public function __construct(Player $player) {
        $this->player = $player;
        $this->checkers = [
            new BlockBreakChecker($this),
        ];
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player {
        return $this->player;
    }

    public function rejoin(Player $player): void {
        $this->player = $player;
    }

    public function onBreakEvent(): void {
        foreach ($this->checkers as $checker) {
            $checker->blockBreak();
        }
    }
}