<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\StackStorageAllForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class StackAllCommand extends Command {

    public function __construct() {
        parent::__construct("stall", "インベントリからストレージにアイテムを一括で移動させることができます", "/stall");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new StackStorageAllForm());
    }
}