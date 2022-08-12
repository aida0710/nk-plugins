<?php

namespace lazyperson0710\PlayerSetting\command;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\TestSetting;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TestCommand extends Command {

    public function __construct() {
        parent::__construct("test", "true/false");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        var_dump($args[0]);
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($sender);
        $setting->getSetting(TestSetting::getName())?->setValue($args[0]);
        $sender->sendMessage($args[0] . "に設定されました");
    }
}