<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\WarpForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WpCommand extends Command {

    public function __construct() {
        parent::__construct("wp", "Warp form");
        $this->setPermission("sff.command.wp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new WarpForm($sender));
    }
}