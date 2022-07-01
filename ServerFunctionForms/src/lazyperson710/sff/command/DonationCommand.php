<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\DonationForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DonationCommand extends Command {

    public function __construct() {
        parent::__construct("donation", "寄付総額に応じた特典を受け取れます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new DonationForm());
    }
}