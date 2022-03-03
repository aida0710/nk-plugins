<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\ShopForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ShopCommand extends Command {

    public function __construct() {
        parent::__construct("shop", "shopを開きます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new ShopForm($sender));
    }
}