<?php

declare(strict_types = 0);
namespace lazyperson0710\ticket\command;

use lazyperson0710\ticket\command\form\MainTicketForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class TicketCommand extends Command {

	public function __construct() {
		parent::__construct('ticket', 'ticket関係');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		SendForm::Send($sender, (new MainTicketForm($sender)));
	}
}
