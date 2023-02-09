<?php

declare(strict_types = 1);
namespace lazyperson710\sff\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\playerInfo\PlayerInfoForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PlayerCommand extends Command {

	public function __construct() {
		parent::__construct('player', 'プレイヤーの情報を取得する');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		SendForm::Send($sender, (new PlayerInfoForm($sender)));
	}
}
