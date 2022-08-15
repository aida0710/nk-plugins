<?php

namespace deceitya\miningtools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\normal\ConfirmForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class NetheriteMiningToolCommand extends Command {

    public function __construct() {
        parent::__construct("mt2", "NetheriteMiningTool");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) <= 29) {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル30以上でないと開けません");
            Server::getInstance()->dispatchCommand($sender, "mt");
            return;
        }
        SendForm::Send($sender, (new ConfirmForm($sender, "netherite")));
    }

}