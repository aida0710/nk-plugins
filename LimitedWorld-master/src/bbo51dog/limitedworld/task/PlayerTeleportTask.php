<?php

namespace bbo51dog\limitedworld\task;

use bbo51dog\limitedworld\WorldProperty;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PlayerTeleportTask extends Task {

    private Player $player;

    private WorldProperty $property;

    /**
     * @param Player $player
     * @param WorldProperty $property
     */
    public function __construct(Player $player, WorldProperty $property) {
        $this->player = $player;
        $this->property = $property;
    }

    /**
     * @inheritDoc
     */
    public function onRun(): void {
        if (!$this->player->isOnline()) {
            return;
        }
        if (!$this->property->inSafeArea($this->player->getPosition())) {
            Server::getInstance()->dispatchCommand($this->player, "warp lobby");
            $this->player->sendMessage("セーフエリアに戻らなかったため、ロビーにテレポートしました");
        }
    }
}