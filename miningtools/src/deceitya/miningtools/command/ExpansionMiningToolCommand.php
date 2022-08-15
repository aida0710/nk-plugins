<?php

namespace deceitya\miningtools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\ExtensionsMainForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class ExpansionMiningToolCommand extends Command {

    public function __construct() {
        parent::__construct("mt3", "NetheriteMiningTool");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) < 250) {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル250以上でないと開けません");
            Server::getInstance()->dispatchCommand($sender, "mt");
            return;
        }
        if ((new CheckPlayerData())->checkMiningToolsNBT($sender) === false) return;
        SendForm::Send($sender, (new ExtensionsMainForm($sender)));
    }

}