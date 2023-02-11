<?php

declare(strict_types = 0);
namespace deceitya\miningtools\command;

use deceitya\miningtools\command\form\MiningToolsForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class MiningToolsCommand extends Command {

	public function __construct() {
		parent::__construct('mt', 'MiningToolShopを開きます');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		SendForm::Send($sender, (new MiningToolsForm($sender)));
	}
}
