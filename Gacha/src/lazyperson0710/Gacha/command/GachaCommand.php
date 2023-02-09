<?php

declare(strict_types = 1);
namespace lazyperson0710\Gacha\command;

use lazyperson0710\Gacha\form\MainForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class GachaCommand extends Command {

	public function __construct() {
		parent::__construct('gacha', 'ガチャを引く');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		SendForm::Send($sender, (new MainForm()));
	}

}
