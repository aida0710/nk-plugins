<?php

declare(strict_types=1);
namespace rib\tw\command;

use pocketmine\command\{Command, CommandSender,};
use pocketmine\player\Player;
use pocketmine\Server;
use rib\tw\form\TeleportForm;

class TeleportCommand extends Command {

    public function __construct() {
        parent::__construct('wtp', 'デバッグ用teleportコマンド', '/wtp');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!($sender instanceof Player) or !Server::getInstance()->isOp($sender->getName())) {
            return false;
        }
        $sender->sendForm(new TeleportForm);
        return true;
    }
}
