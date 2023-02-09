<?php

declare(strict_types = 1);
namespace Deceitya\NotionForm\command;

use Deceitya\NotionForm\Form\StartForm;
use Deceitya\NotionForm\Main;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SpecificationCommand extends Command {

	public function __construct() {
		parent::__construct('specification', '鯖の仕様を説明しています');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		SendForm::Send($sender, (new StartForm(Main::$specification)));
	}

}
