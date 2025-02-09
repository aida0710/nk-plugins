<?php

declare(strict_types = 0);

namespace onebone\economyapi\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function array_shift;
use function is_numeric;

class TakeMoneyCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        $desc = $plugin->getCommandMessage('takemoney');
        parent::__construct('takemoney', $desc['description'], $desc['usage']);
        $this->setPermission('economyapi.command.takemoney');
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if (!$this->plugin->isEnabled()) return false;
        if (!$this->testPermission($sender)) {
            return false;
        }
        $player = array_shift($params);
        $amount = array_shift($params);
        if (!is_numeric($amount)) {
            $sender->sendMessage(TextFormat::RED . 'Usage: ' . $this->getUsage());
            return true;
        }
        if (($p = $this->plugin->getServer()->getPlayerByPrefix($player)) instanceof Player) {
            $player = $p->getName();
        }
        if ($amount < 0) {
            $sender->sendMessage($this->plugin->getMessage('takemoney-invalid-number', [$amount], $sender->getName()));
            return true;
        }
        $result = $this->plugin->reduceMoney($player, $amount, false, 'economyapi.command.take');
        switch ($result) {
            case EconomyAPI::RET_INVALID:
                $sender->sendMessage($this->plugin->getMessage('takemoney-player-lack-of-money', [$player, $amount, $this->plugin->myMoney($player)], $sender->getName()));
                break;
            case EconomyAPI::RET_SUCCESS:
                $sender->sendMessage($this->plugin->getMessage('takemoney-took-money', [$player, $amount], $sender->getName()));
                if ($p instanceof Player) {
                    $p->sendMessage($this->plugin->getMessage('takemoney-money-taken', [$amount], $sender->getName()));
                }
                break;
            case EconomyAPI::RET_CANCELLED:
                $sender->sendMessage($this->plugin->getMessage('takemoney-failed', [], $sender->getName()));
                break;
            case EconomyAPI::RET_NO_ACCOUNT:
                $sender->sendMessage($this->plugin->getMessage('player-never-connected', [$player], $sender->getName()));
                break;
        }
        return true;
    }
}
