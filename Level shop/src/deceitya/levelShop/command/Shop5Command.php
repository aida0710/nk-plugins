<?php

namespace deceitya\levelShop\command;

use deceitya\levelShop\form\shop5\Shop5Form;
use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Shop5Command extends Command {

    public function __construct() {
        parent::__construct("shop5", "LevelShop5が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 120) {
            $sender->sendForm(new Shop5Form());
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル120以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}