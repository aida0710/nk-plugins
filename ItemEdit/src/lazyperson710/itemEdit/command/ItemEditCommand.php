<?php

namespace lazyperson710\itemEdit\command;

use lazyperson710\itemEdit\form\NbtEditForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class ItemEditCommand extends Command {

    public function __construct() {
        parent::__construct("edit", "ItemEditor");
        $this->setPermission("itemEditor.command.edit");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) return;
        if (!Server::getInstance()->isOp($sender->getName())) return;
        $sender->sendForm(new NbtEditForm($sender));
    }
}