<?php

declare(strict_types = 0);

namespace deceitya\editEnchant\command;

use deceitya\editEnchant\form\DelForm;
use deceitya\editEnchant\InspectionItem;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DeleteCommand extends Command {

	public function __construct() {
		parent::__construct('endelete', 'エンチャントを削除する');
	}

	public function execute(CommandSender $sender, string $label, array $args) : bool {
		if (!($sender instanceof Player)) {
			return true;
		}
		if (!(new InspectionItem())->inspectionItem($sender)) return true;
		SendForm::Send($sender, (new DelForm($sender)));
		return true;
	}

}
