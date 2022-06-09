<?php

namespace deceitya\miningtools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\normal\ConfirmForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class DiamondMiningToolCommand extends Command {

    public function __construct() {
        parent::__construct("mt1", "DiamondMiningTool");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        //todo テスト必須
        if (MiningLevelAPI::getInstance()->getLevel($sender) <= 16) {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル15以上でないと開けません");
            Server::getInstance()->dispatchCommand($sender, "mt");
        }
        $sender->sendForm(new ConfirmForm($sender, "diamond"));
    }

}