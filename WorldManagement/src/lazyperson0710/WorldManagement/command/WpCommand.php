<?php

namespace lazyperson0710\WorldManagement\command;

use lazyperson0710\WorldManagement\form\WarpForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WpCommand extends Command {

    public function __construct() {
        parent::__construct("wp", "ワープフォームを開きます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new WarpForm($sender));
    }
}