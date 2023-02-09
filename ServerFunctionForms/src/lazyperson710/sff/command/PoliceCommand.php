<?php

declare(strict_types = 1);
namespace lazyperson710\sff\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\sff\form\police\PoliceMainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function in_array;

class PoliceCommand extends Command {

	public function __construct() {
		parent::__construct('p', 'debugコマンド');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		$players = [
			'lazyperson710',
			'Oasobiyou',
			'ichigoame lili',
			'toppo0118',
		];
		if (in_array($sender->getName(), $players, true)) {
			SendForm::Send($sender, (new PoliceMainForm($sender)));
		} else {
			SendMessage::Send($sender, 'コマンドの使用権限がありません', 'System', false);
		}
	}
}
