<?php

declare(strict_types=1);
namespace onebone\economyapi\command;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\PayCommandUseSetting;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use onebone\economyapi\event\money\PayMoneyEvent;
use onebone\economyapi\form\PayCommandConfirmation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use function array_shift;
use function is_numeric;
use function strpos;

class PayCommand extends Command {

	public function __construct(private EconomyAPI $plugin) {
		parent::__construct("pay", "他プレイヤーに送金する");
		$this->setPermission("economyapi.command.pay");
	}

	public function execute(CommandSender $sender, string $label, array $params) : bool {
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
			SendMessage::Send($sender, "/pay <player> <amount>で使用することが可能です", "Economy", true);
			return true;
		}
		if (strpos($amount, '.')) {
			SendMessage::Send($sender, "振り込む金額に小数点を含めることはできません", "Economy", false);
			return true;
		}
		if (!Server::getInstance()->getPlayerByPrefix($target) instanceof Player) {
			SendMessage::Send($sender, "{$target}は現在オフラインの為送金できませんでした", "Economy", false);
			return true;
		}
		$target = Server::getInstance()->getPlayerByPrefix($target);
		if (!$this->plugin->accountExists($target)) {
			SendMessage::Send($sender, "{$target}はアカウントデータが存在しない為送金できませんでした", "Economy", false);
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
			SendMessage::Send($player, "{$target->getName()}に{$amount}円を送金しました", "Economy", true);
			SendMessage::Send($target, "{$player->getName()}から{$amount}円を受け取りました", "Economy", true);
		} else {
			SendMessage::Send($player, "不明な原因により送金に失敗しました", "Economy", false);
		}
	}
}
