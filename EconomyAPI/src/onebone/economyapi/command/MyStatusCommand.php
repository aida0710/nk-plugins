<?php

declare(strict_types = 0);

namespace onebone\economyapi\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function round;
use function strtolower;

class MyStatusCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        $desc = $plugin->getCommandMessage('mystatus');
        parent::__construct('mystatus', $desc['description'], $desc['usage']);
        $this->setPermission('economyapi.command.mystatus');
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if (!$this->plugin->isEnabled()) return false;
        if (!$this->testPermission($sender)) {
            return false;
        }
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . 'Please run this command in-game.');
            return true;
        }
        $money = $this->plugin->getAllMoney();
        $allMoney = 0;
        foreach ($money as $m) {
            $allMoney += $m;
        }
        $topMoney = 0;
        if ($allMoney > 0) {
            $topMoney = round((($money[strtolower($sender->getName())] / $allMoney) * 100), 2);
        }
        $sender->sendMessage($this->plugin->getMessage('mystatus-show', [$topMoney], $sender->getName()));
        return true;
    }
}
