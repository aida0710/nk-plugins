<?php

declare(strict_types = 1);

namespace lazyperson0710\ShopSystem\command;

use lazyperson0710\ShopSystem\form\levelShop\CategorySelectForm;
use lazyperson0710\ShopSystem\form\levelShop\future\LevelConfirmation;
use lazyperson0710\ShopSystem\form\levelShop\future\RestrictionShop;
use lazyperson0710\ShopSystem\form\levelShop\other\OtherShopFunctionSelectForm;
use lazyperson0710\ShopSystem\form\levelShop\ShopSelectForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ShopCommand extends Command {

	public function __construct() {
		parent::__construct('shop', 'ItemShopを開きます - /shop [shopNumber]');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
		if (!($sender instanceof Player)) {
			$sender->sendMessage('サーバー内で実行してください');
			return;
		}
		match ($args[0]) {
			'0' => LevelConfirmation::getInstance()->levelConfirmation($sender, new OtherShopFunctionSelectForm, RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'1' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(1), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'2' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(2), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'3' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(3), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'4' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(4), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'5' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(5), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'6' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(6), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			'7' => LevelConfirmation::getInstance()->levelConfirmation($sender, new CategorySelectForm(7), RestrictionShop::getInstance()->getRestrictionByShopNumber($args[0])),
			default => SendForm::Send($sender, new ShopSelectForm($sender)),
		};
		throw new \RuntimeException('Invalid shop number');
	}
}
