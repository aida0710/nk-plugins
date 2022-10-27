<?php

namespace lazyperson710\core\command;

use lazyperson710\core\listener\JoinPlayerEvent;
use lazyperson710\core\packet\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class JoinItemCommand extends Command {

    public function __construct() {
        parent::__construct("joinitem", "参加時にもらえるアイテムを再取得できます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("サーバー内で実行してください");
            return;
        }
        JoinPlayerEvent::sendJoinItem($sender);
        SendMessage::Send($sender, "アイテムを付与しました", "SendJoinItem", true);
    }

}