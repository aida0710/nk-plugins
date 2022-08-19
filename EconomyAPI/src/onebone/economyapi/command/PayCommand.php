<?php

namespace onebone\economyapi\command;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\PayCommandUseSetting;
use lazyperson710\core\packet\SendForm;
use onebone\economyapi\EconomyAPI;
use onebone\economyapi\event\money\PayMoneyEvent;
use onebone\economyapi\form\PayCommandConfirmation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class PayCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        parent::__construct("pay", "他プレイヤーに送金する");
        $this->setPermission("economyapi.command.pay");
    }

    public function execute(CommandSender $sender, string $label, array $params): bool {
        if (!$this->plugin->isEnabled()) return false;
        if (!$this->testPermission($sender)) {
            return false;
        }
        if (!$sender instanceof Player) {
            $sender->sendMessage("Please run this command in-game.");
            return true;
        }
        $target = array_shift($params);
        $amount = array_shift($params);
        if (!is_numeric($amount)) {
            $sender->sendMessage("§bEconomy §7>> §c/pay <player> <amount>で使用することが可能です");
            return true;
        }
        if (strpos($amount, '.')) {
            $sender->sendMessage("§bEconomy §7>> §c振り込む金額に小数点を含めることはできません");
            return true;
        }
        if (!Server::getInstance()->getPlayerByPrefix($target) instanceof Player) {
            $sender->sendMessage("§bEconomy §7>> §c{$target->getName()}は現在オフラインの為送金できませんでした");
            return true;
        }
        $target = Server::getInstance()->getPlayerByPrefix($target);
        if (!$this->plugin->accountExists($target)) {
            $sender->sendMessage("§bEconomy §7>> §c{$target->getName()}はアカウントデータが存在しない為送金できませんでした");
            return true;
        }
        if (PlayerSettingPool::getInstance()->getSettingNonNull($sender)->getSetting(PayCommandUseSetting::getName())?->getValue() === true) {
            SendForm::Send($sender, new PayCommandConfirmation($this->plugin, $target, $amount));
        } else {
            $this->Calculation($this->plugin, $sender, $target, $amount);
        }
        return true;
    }

    public function Calculation(EconomyAPI $plugin, Player $player, Player $target, int $amount) {
        $ev = new PayMoneyEvent($plugin, $player->getName(), $target, $amount);
        $ev->call();
        $result = EconomyAPI::RET_CANCELLED;
        if (!$ev->isCancelled()) {
            $result = $plugin->reduceMoney($player, $amount, false, 'economyapi.command.pay');
        }
        if ($result === EconomyAPI::RET_SUCCESS) {
            $plugin->addMoney($target, $amount, true, 'economyapi.command.pay');
            $player->sendMessage("§bEconomy §7>> §a{$target->getName()}に{$amount}円を送金しました");
            $target->sendMessage("§bEconomy §7>> §a{$player->getName()}から{$amount}円を受け取りました");
        } else {
            $player->sendMessage("§bEconomy §7>> §c不明な原因により送金に失敗しました");
        }
    }
}
