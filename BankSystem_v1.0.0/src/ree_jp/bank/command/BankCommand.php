<?php

declare(strict_types = 1);
namespace ree_jp\bank\command;

use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\player\Player;
use ree_jp\bank\form\BankForm;

class BankCommand extends Command {

	public function __construct(string $name = 'bank', string $description = '銀行システム', string $usageMessage = null, array $aliases = []) {
		$overloads = [
			[
				CommandParameter::enum('bankMode', new CommandEnum('bankMode', ['ranking', 'create', 'delete', 'log', 'put', 'out', 'share', 'transfer']), AvailableCommandsPacket::ARG_TYPE_STRING, true),
				CommandParameter::standard('bank', AvailableCommandsPacket::ARG_TYPE_STRING),
				CommandParameter::standard('money', AvailableCommandsPacket::ARG_TYPE_INT),
			],
		];
		parent::__construct($name, $description, $usageMessage, $aliases, $overloads);
		$this->setPermission('command.banksystem.true');
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if ($sender instanceof Player) {
			if ($this->testPermission($sender)) {
				SendForm::Send($sender, (new BankForm()));
			}
		} else {
			$sender->sendMessage('サーバー内で実行してください');
		}
		return true;
	}
}
