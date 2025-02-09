<?php

declare(strict_types = 0);

namespace onebone\economyapi\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function array_shift;
use function trim;

class SeeMoneyCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        $desc = $plugin->getCommandMessage('seemoney');
        parent::__construct('seemoney', $desc['description'], $desc['usage']);
        $this->setPermission('economyapi.command.seemoney');
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if (!$this->plugin->isEnabled()) return false;
        if (!$this->testPermission($sender)) {
            return false;
        }
        $player = array_shift($params);
        if (trim($player) === '') {
            $sender->sendMessage(TextFormat::RED . 'Usage: ' . $this->getUsage());
            return true;
        }
        if (($p = $this->plugin->getServer()->getPlayerByPrefix($player)) instanceof Player) {
            $player = $p->getName();
        }
        $money = $this->plugin->myMoney($player);
        if ($money !== false) {
            $sender->sendMessage($this->plugin->getMessage('seemoney-seemoney', [$player, $money], $sender->getName()));
        } else {
            $sender->sendMessage($this->plugin->getMessage('player-never-connected', [$player], $sender->getName()));
        }
        return true;
    }
}
