<?php

namespace deceitya\miningTools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningTools\netherite\NetheriteToolForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class NetheriteMiningToolCommand extends Command {

    public function __construct() {
        parent::__construct("mt2", "NetheriteMiningTool");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 50) {
            $sender->sendForm(new NetheriteToolForm());
        } else {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル50以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "mt2");
        }
    }

}