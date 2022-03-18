<?php

namespace deceitya\miningTools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningTools\diamond\DiamondToolForm;
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
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 25) {
            $sender->sendForm(new DiamondToolForm());
        } else {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル25以上でないと開けません。");
            Server::getInstance()->dispatchCommand($sender, "mt1");
        }
    }

}