<?php

namespace deceitya\ShopAPI\command;

use deceitya\ShopAPI\form\levelShop\shop1\Shop1Form;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class EffectShopCommand extends Command {

    public function __construct() {
        parent::__construct("ef", "エフェクトショップを開くことができます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $sender->sendForm(new Shop1Form());
    }
}