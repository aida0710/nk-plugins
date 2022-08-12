<?php

namespace lazyperson0710\PlayerSetting\command;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CheckCommand extends Command {

    public function __construct() {
        parent::__construct("check", "現状の確認");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($sender);
        var_dump($setting->toArray());
    }
}