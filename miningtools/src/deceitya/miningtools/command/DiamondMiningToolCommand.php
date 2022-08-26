<?php

namespace deceitya\miningtools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\normal\ConfirmForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class DiamondMiningToolCommand extends Command {

    public const DiamondMiningToolsLevelLimit = 15;

    public function __construct() {
        parent::__construct("dmt", "DiamondMiningTool");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) < self::DiamondMiningToolsLevelLimit) {
            $sender->sendMessage("§bMiningToolShop §7>> §cレベル" . self::DiamondMiningToolsLevelLimit . "以上でないと開けません");
            Server::getInstance()->dispatchCommand($sender, "mt");
            return;
        }
        SendForm::Send($sender, (new ConfirmForm($sender, "diamond")));
    }

}