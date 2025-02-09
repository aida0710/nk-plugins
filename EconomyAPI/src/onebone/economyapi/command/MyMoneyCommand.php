<?php

declare(strict_types = 0);

namespace onebone\economyapi\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MyMoneyCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        $desc = $plugin->getCommandMessage('mymoney');
        parent::__construct('mymoney', $desc['description'], $desc['usage']);
        $this->setPermission('economyapi.command.mymoney');
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if (!$this->plugin->isEnabled()) return false;
        if (!$this->testPermission($sender)) {
            return false;
        }
        if ($sender instanceof Player) {
            $money = $this->plugin->myMoney($sender);
            $sender->sendMessage($this->plugin->getMessage('mymoney-mymoney', [$money]));
            return true;
        }
        $sender->sendMessage(TextFormat::RED . 'Please run this command in-game.');
        return true;
    }
}
