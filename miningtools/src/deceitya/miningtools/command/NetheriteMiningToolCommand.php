<?php

namespace deceitya\miningtools\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\miningtools\normal\ConfirmForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class NetheriteMiningToolCommand extends Command {

    public function __construct() {
        parent::__construct("nmt", "NetheriteMiningTool");
    }

    public const NetheriteMiningToolsLevelLimit = 30;

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("サーバー内で実行してください");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($sender) < self::NetheriteMiningToolsLevelLimit) {
            SendMessage::Send($sender, "レベル" . self::NetheriteMiningToolsLevelLimit . "以上でないと開けません", "MiningTool", false);
            Server::getInstance()->dispatchCommand($sender, "mt");
            return;
        }
        SendForm::Send($sender, (new ConfirmForm($sender, "netherite")));
    }

}