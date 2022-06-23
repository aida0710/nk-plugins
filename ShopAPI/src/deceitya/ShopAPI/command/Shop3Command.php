<?php

namespace deceitya\ShopAPI\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\form\levelShop\shop3\Shop3Form;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class Shop3Command extends Command {

    public function __construct() {
        parent::__construct("shop3", "LevelShop3が開けます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 50) {
            $sender->sendForm(new Shop3Form());
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル50以上でないと開けません");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}