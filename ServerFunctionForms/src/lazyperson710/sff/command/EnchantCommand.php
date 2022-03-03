<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\EnchantForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class EnchantCommand extends Command {

    public function __construct() {
        parent::__construct("en", "enchantを付けたり外したりできます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new EnchantForm($sender));
    }
}