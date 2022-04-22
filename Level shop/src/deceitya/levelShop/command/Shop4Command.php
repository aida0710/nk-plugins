<?php

namespace deceitya\levelShop\command;

use deceitya\levelShop\form\shop4\Shop4Form;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Shop4Command extends Command {

    public function __construct() {
        parent::__construct("shop4", "LevelShop4が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 80) {
            $sender->sendForm(new Shop4Form());
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル80以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}