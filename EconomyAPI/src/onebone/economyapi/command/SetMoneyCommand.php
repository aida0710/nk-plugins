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

class SetMoneyCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        $desc = $plugin->getCommandMessage('setmoney');
        parent::__construct('setmoney', $desc['description'], $desc['usage']);
        $this->setPermission('economyapi.command.setmoney');
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
        $result = $this->plugin->setMoney($player, $amount, false, 'economyapi.command.set');
        switch ($result) {
            case EconomyAPI::RET_INVALID:
                $sender->sendMessage($this->plugin->getMessage('setmoney-invalid-number', [$amount], $sender->getName()));
                break;
            case EconomyAPI::RET_NO_ACCOUNT:
                $sender->sendMessage($this->plugin->getMessage('player-never-connected', [$player], $sender->getName()));
                break;
            case EconomyAPI::RET_CANCELLED:
                $sender->sendMessage($this->plugin->getMessage('setmoney-failed', [], $sender->getName()));
                break;
            case EconomyAPI::RET_SUCCESS:
                $sender->sendMessage($this->plugin->getMessage('setmoney-setmoney', [$player, $amount], $sender->getName()));
                if ($p instanceof Player) {
                    $p->sendMessage($this->plugin->getMessage('setmoney-set', [$amount], $p->getName()));
                }
                break;
            default:
                $sender->sendMessage('WTF');
        }
        return true;
    }
}
