<?php

namespace deceitya\levelShop\command;

use deceitya\levelShop\form\shop2\Shop2Form;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Shop2Command extends Command {

    public function __construct() {
        parent::__construct("shop2", "LevelShop2が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 25) {
            $sender->sendForm(new Shop2Form());
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル25以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}