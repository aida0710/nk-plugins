<?php

namespace lazyperson710\core\command;

use lazyperson710\core\form\FlyForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FlyCommand extends Command {

    public function __construct() {
        parent::__construct("fly", "お金を消費して飛べます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new FlyForm($sender));
    }
}