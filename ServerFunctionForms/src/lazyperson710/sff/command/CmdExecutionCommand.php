<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\CommandExecutionForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CmdExecutionCommand extends Command {

    public function __construct() {
        parent::__construct("cmd", "CommandExecutionFormを表示します");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new CommandExecutionForm($sender));
    }
}