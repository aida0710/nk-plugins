<?php

namespace lazyperson710\sff\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\TosForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TosCommand extends Command {

    public function __construct() {
        parent::__construct("tos", "Terms of Service form");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        SendForm::Send($sender, (new TosForm()));
    }
}