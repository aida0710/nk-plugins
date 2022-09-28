<?php

namespace lazyperson710\edit\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\edit\form\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class ItemEditCommand extends Command {

    public function __construct() {
        parent::__construct("edit", "色んな機能を改変する");
        $this->setPermission("itemEditor.command.edit");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) return;
        if (!Server::getInstance()->isOp($sender->getName())) return;
        SendForm::Send($sender, (new MainForm($sender)));
    }
}