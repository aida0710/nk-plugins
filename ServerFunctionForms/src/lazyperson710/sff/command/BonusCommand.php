<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\BonusForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BonusCommand extends Command {

    public function __construct() {
        parent::__construct("bonus", "ログインボーナス関係の操作ができます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new BonusForm($sender));
    }
}