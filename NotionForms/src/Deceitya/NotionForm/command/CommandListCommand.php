<?php

namespace Deceitya\NotionForm\command;

use Deceitya\NotionForm\Form\StartForm;
use Deceitya\NotionForm\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CommandListCommand extends Command {

    public function __construct() {
        parent::__construct("cmdls", "鯖内で使用できるコマンドの機能や仕様が書かれています");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new StartForm(Main::$command));
    }

}