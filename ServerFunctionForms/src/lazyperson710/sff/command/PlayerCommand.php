<?php

namespace lazyperson710\sff\command;

use lazyperson710\sff\form\PlayerInfoForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PlayerCommand extends Command {

    public function __construct() {
        parent::__construct("player", "プレイヤーの情報を取得する");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new PlayerInfoForm($sender));
    }
}