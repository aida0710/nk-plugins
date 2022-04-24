<?php

namespace lazyperson710\itemEdit\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class nbtEditCommand extends Command {

    public function __construct() {
        parent::__construct("nbt", "nbtEditCommand");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) return;
        if (!Server::getInstance()->isOp($sender->getName())) return;
        $item = $sender->getInventory()->getItemInHand();
        $nbt = $item->getNamedTag();
        if (empty($args)){
            $sender->sendMessage("付与したいnbtタグをサブコマンドとして入力してください");
        }
        foreach ($args as $sub) {
            $nbt->setInt($sub, 1);
            $sender->getInventory()->setItemInHand($item);
            $sender->sendMessage("nbtタグ {$sub} を付与しました");
        }
    }
}