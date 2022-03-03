<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\MiningToolsForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MiningToolsCommand extends Command {

    public function __construct() {
        parent::__construct("mt", "MiningToolShopを開きます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new MiningToolsForm($sender));
    }
}