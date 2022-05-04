<?php

namespace lazyperson0710\ticket\command;

use lazyperson0710\ticket\command\form\MainTicketForm;
use lazyperson0710\ticket\command\form\ReplaceTicketForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class TicketCommand extends Command {

    public function __construct() {
        parent::__construct("ticket", "ticket関係");
        $this->setPermission("ticketApi.command.ticket");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (Server::getInstance()->isOp($sender->getName())) {
            $sender->sendForm(new MainTicketForm($sender));
        }
        $sender->sendForm(new ReplaceTicketForm($sender));
    }
}