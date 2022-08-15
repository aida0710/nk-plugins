<?php

namespace deceitya\ShopAPI\command;

use deceitya\ShopAPI\form\enchantShop\EnchantSelectForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class EnchantShopCommand extends Command {

    public function __construct() {
        parent::__construct("ven", "エンチャントショップを開くことができます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        SendForm::Send($sender, (new EnchantSelectForm()));
    }
}