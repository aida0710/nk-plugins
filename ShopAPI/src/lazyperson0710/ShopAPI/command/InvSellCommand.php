<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\command;

use deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\ShopAPI\form\levelShop\other\InvSell\Confirmation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class InvSellCommand extends Command {

	public function __construct() {
		parent::__construct("invsell", "inventoryのアイテムを一括売却します");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage("サーバー内で実行してください");
			return;
		}
		if (MiningLevelAPI::getInstance()->getLevel($sender) >= 25) {
			SendForm::Send($sender, (new Confirmation()));
		} else {
			SendMessage::Send($sender, "レベル25以上でないと実行できません", "LevelShop", false);
			Server::getInstance()->dispatchCommand($sender, "shop");
		}
	}
}
