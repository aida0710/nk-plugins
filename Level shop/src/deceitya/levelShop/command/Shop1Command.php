<?php

namespace deceitya\levelShop\command;

use deceitya\levelShop\form\shop1\Shop1Form;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class Shop1Command extends Command {

    public function __construct() {
        parent::__construct("shop1", "LevelShop1が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new Shop1Form());
    }
}