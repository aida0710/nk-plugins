<?php

declare(strict_types=1);
namespace lazyperson0710\blockLogger\command;

use lazyperson0710\blockLogger\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LogCommand extends Command {

    private Main $main;

    public function __construct(Main $main) {
        $this->main = $main;
        parent::__construct("log", "ログの確認", "/log [x] [y] [z] [world]");
        $this->setPermission("space.lazyperson0710.log");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) return false;
        if (!$this->testPermission($sender)) return false;
        if (!isset($args[0])) {
            $msg = ["ON", "OFF"];
            $this->main->changeParam($sender);
            $flag = $this->main->isOn($sender) ? 0 : 1;
            $sender->sendMessage("[Main]§a{$msg[$flag]}にしました");
            return true;
        }
        if (isset($args[1]) && isset($args[2]) && isset($args[3])) {
            if (is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])) {
                $cls = $this->main->getDatabase();
                $cls->checklog($args[0], $args[1], $args[2], $args[3], $sender);
                return true;
            }
        }
        return true;
    }
}