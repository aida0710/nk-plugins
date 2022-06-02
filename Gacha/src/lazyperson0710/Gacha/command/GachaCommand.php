<?php

namespace lazyperson710\Gacha\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class GachaCommand extends Command {

    public function __construct() {
        parent::__construct("book", "50円で署名済みの本を一冊複製する");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        //ここからformを出す感じ
    }

}