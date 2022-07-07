<?php

namespace deceitya\ShopAPI\command;

use deceitya\ShopAPI\form\levelShop\MainLevelShopForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ShopCommand extends Command {

    public function __construct() {
        parent::__construct("shop", "shopを開きます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new MainLevelShopForm($sender));
    }
}