<?php

namespace deceitya\ecoshop\command;

use deceitya\ecoshop\form\shop7\Shop7Form;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Shop7Command extends Command {

    public function __construct() {
        parent::__construct("shop7", "LevelShop7が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 250) {
            $sender->sendForm(new Shop7Form());
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル250以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}