<?php

namespace deceitya\levelShop\command;

use deceitya\levelShop\form\shop6\Shop6Form;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Shop6Command extends Command {

    public function __construct() {
        parent::__construct("shop6", "LevelShop6が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 180) {
            $sender->sendForm(new Shop6Form());
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル180以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}