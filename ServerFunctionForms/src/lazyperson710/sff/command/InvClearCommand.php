<?php

namespace lazyperson710\sff\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\InvClearForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class InvClearCommand extends Command {

    public function __construct() {
        parent::__construct("invclear", "インベントリからアイテムを削除します");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("サーバー内で実行してください");
            return;
        }
        SendForm::Send($sender, (new InvClearForm()));
    }
}