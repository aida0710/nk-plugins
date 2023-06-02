<?php

declare(strict_types = 0);

namespace lazyperson0710\WorldManagement\WorldLimit\task;

use lazyperson0710\WorldManagement\WorldLimit\WorldProperty;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PlayerTeleportTask extends Task {

    private Player $player;

    private WorldProperty $property;

    public function __construct(Player $player, WorldProperty $property) {
        $this->player = $player;
        $this->property = $property;
    }

    /**
     * @inheritDoc
     */
    public function onRun() : void {
        if (!$this->player->isOnline()) {
            return;
        }
        if (!$this->property->inSafeArea($this->player->getPosition())) {
            Server::getInstance()->dispatchCommand($this->player, 'warp lobby');
            SendMessage::Send($this->player, 'セーフエリアに戻らなかったため、ロビーにテレポートしました', 'WorldBorder', false);
        }
    }
}
