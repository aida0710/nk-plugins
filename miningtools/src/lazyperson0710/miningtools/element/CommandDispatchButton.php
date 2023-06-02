<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use pocketmine\player\Player;
use pocketmine\Server;

class CommandDispatchButton extends Button {

    private string $command;

    public function __construct(string $text, string $command, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->command = $command;
    }

    public function handleSubmit(Player $player) : void {
        Server::getInstance()->dispatchCommand($player, $this->command);
    }
}
