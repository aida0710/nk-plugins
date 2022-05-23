<?php

namespace lazyperson0710\ticket\command;

use lazyperson0710\ticket\command\form\MainTicketForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TicketCommand extends Command {

    public function __construct() {
        parent::__construct("ticket", "ticket関係");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new MainTicketForm($sender));
    }
}