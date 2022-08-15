<?php

namespace Deceitya\Flytra\command;

use Deceitya\Flytra\form\FlyForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FlyCommand extends Command {

    public function __construct() {
        parent::__construct("fly", "お金を消費して飛べます");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        SendForm::Send($sender, (new FlyForm($sender)));
    }
}