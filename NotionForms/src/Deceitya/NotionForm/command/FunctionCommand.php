<?php

namespace Deceitya\NotionForm\command;

use Deceitya\NotionForm\Form\StartForm;
use Deceitya\NotionForm\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FunctionCommand extends Command {

    public function __construct() {
        parent::__construct("function", "鯖の仕様を説明しています");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new StartForm(Main::$function));
    }

}