<?php

namespace deceitya\ShopAPI\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\form\levelShop\other\InvSell\Confirmation;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class InvSellCommand extends Command {

    public function __construct() {
        parent::__construct("invsell", "inventoryのアイテムを一括売却します");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 25) {
            SendForm::Send($sender, (new Confirmation()));
        } else {
            $sender->sendMessage("§bLevelShop §7>> §cレベル25以上でないと実行できません");
            Server::getInstance()->dispatchCommand($sender, "shop");
        }
    }
}