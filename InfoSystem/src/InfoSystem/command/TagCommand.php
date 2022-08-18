<?php

namespace InfoSystem\command;

use InfoSystem\form\InputTagForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TagCommand extends Command {

    public function __construct() {
        parent::__construct("tag", "自身のtag(称号)を変更する");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        SendForm::Send($sender, new InputTagForm());
    }

}