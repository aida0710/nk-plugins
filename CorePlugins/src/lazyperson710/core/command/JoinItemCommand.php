<?php

namespace lazyperson710\core\command;

use lazyperson710\core\listener\JoinPlayerEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class JoinItemCommand extends Command {

    public function __construct() {
        parent::__construct("joinitem", "参加時にもらえるアイテムを再取得できます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        JoinPlayerEvent::sendJoinItem($sender);
        $sender->sendMessage("§bSendJoinItem §7>> §aアイテムを付与しました");
    }

}