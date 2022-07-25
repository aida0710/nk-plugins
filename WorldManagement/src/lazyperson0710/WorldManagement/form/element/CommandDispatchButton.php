<?php

namespace lazyperson0710\WorldManagement\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\WorldManagement\form\WarpForm;
use pocketmine\player\Player;
use pocketmine\Server;

class CommandDispatchButton extends Button {

    private string $command;
    private bool $permission;

    public function __construct(string $text, string $command, bool $permission, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->command = $command;
        $this->permission = $permission;
    }

    public function handleSubmit(Player $player): void {
        if ($this->permission === true) {
            Server::getInstance()->dispatchCommand($player, $this->command);
        } else {
            $error = "\n§c選択したワールドはレベルが足りないか解放されていません";
            $player->sendForm(new WarpForm($player, $error));
        }
    }
}