<?php

namespace lazyperson0710\LoginBonus\command;

use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson0710\LoginBonus\form\convert\ItemSelectForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LoginBonusCommand extends Command {

    public function __construct() {
        parent::__construct("bonus", "ログインボーナス関係のformを開きます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        SendForm::Send($sender, (new BonusForm($sender)));
    }
}