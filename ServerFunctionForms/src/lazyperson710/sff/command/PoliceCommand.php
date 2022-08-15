<?php

namespace lazyperson710\sff\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\police\PoliceMainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PoliceCommand extends Command {

    public function __construct() {
        parent::__construct("p", "debugコマンド");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $players = [
            "lazyperson710",
        ];
        if (in_array($sender->getName(), $players)) {
            SendForm::Send($sender, (new PoliceMainForm($sender)));
        } else {
            $sender->sendMessage("§bSystem §7 >> §aコマンドの使用権限がありません");
        }
    }
}