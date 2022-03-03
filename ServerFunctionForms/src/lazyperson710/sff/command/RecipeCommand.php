<?php

namespace lazyperson710\sff\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class RecipeCommand extends Command {

    public function __construct() {
        parent::__construct("recipe", "オリジナルRecipeを見れます(teleportするので注意)");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        Server::getInstance()->dispatchCommand($sender, "warp recipe");
    }
}