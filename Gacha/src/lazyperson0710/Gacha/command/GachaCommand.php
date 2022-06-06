<?php

namespace lazyperson0710\Gacha\command;

use lazyperson0710\Gacha\form\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class GachaCommand extends Command {

    public function __construct() {
        parent::__construct("gacha", "ガチャを引く");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new MainForm());
    }

}