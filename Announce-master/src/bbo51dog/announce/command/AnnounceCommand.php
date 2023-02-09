<?php

declare(strict_types = 1);
namespace bbo51dog\announce\command;

use bbo51dog\announce\form\AnnounceForm;
use bbo51dog\announce\service\AnnounceService;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class AnnounceCommand extends Command {

	public function __construct() {
		parent::__construct('announce', 'View latest announce');
		$this->setPermission('announce.command.announce');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if (!$sender instanceof Player) {
			$sender->sendMessage(TextFormat::RED . 'ゲーム内で実行してください');
			return;
		}
		if (AnnounceService::canRead($sender->getName())) {
			SendForm::Send($sender, (new AnnounceForm(AnnounceService::getAnnounceIdByName($sender->getName()))));
			AnnounceService::setAlreadyRead($sender->getName(), true);
		} else {
			SendMessage::Send($sender, '表示するアナウンスがありません', 'Notice', false);
		}
	}
}
