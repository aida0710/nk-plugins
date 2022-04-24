<?php

namespace lazyperson710\itemEdit\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\Server;

class UnbreakableCommand extends Command {

    public function __construct() {
        parent::__construct("break", "UnbreakableCommand");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) return;
        if (!Server::getInstance()->isOp($sender->getName())) return;
        $item = $sender->getInventory()->getItemInHand();
        switch (current($args)) {
            case "on":
                if (!($item instanceof Durable)) {
                    $sender->sendMessage("そのアイテムには適用できません");
                    return;
                }
                $item->setUnbreakable(true);
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("耐久値を無限にしました");
                break;
            case "off":
                if (!($item instanceof Durable)) {
                    $sender->sendMessage("そのアイテムには適用できません");
                    return;
                }
                $item->setUnbreakable(false);
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage("耐久値を有限にしました");
                break;
            default:
                $sender->sendMessage("on/offをサブコマンドとして入力してください");
        }
    }
}