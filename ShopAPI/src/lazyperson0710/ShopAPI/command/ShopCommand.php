<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\command;

use lazyperson0710\ShopAPI\form\levelShop\MainLevelShopForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ShopCommand extends Command {

	public function __construct() {
		parent::__construct("shop", "shopを開きます");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!($sender instanceof Player)) {
			$sender->sendMessage("サーバー内で実行してください");
			return;
		}
		SendForm::Send($sender, (new MainLevelShopForm($sender)));
	}
}
