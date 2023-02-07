<?php

declare(strict_types=1);
namespace bbo51dog\announce;

use bbo51dog\announce\form\AnnounceForm;
use bbo51dog\announce\service\AnnounceService;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use function explode;

class EventListener implements Listener {

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		if (!AnnounceService::existsUser($name)) {
			AnnounceService::createUser($name);
		}
		if (AnnounceService::canRead($name) && !AnnounceService::hasAlreadyRead($name)) {
			SendForm::Send($player, (new AnnounceForm(AnnounceService::getAnnounceIdByName($name))));
			AnnounceService::setAlreadyRead($name, true);
		}
	}

	public function onCommand(CommandEvent $event) {
		$str = explode(" ", $event->getCommand(), 2);
		if ($str[0] === "pass") {
			return;
		}
		$sender = $event->getSender();
		if (!$sender instanceof Player) {
			return;
		}
		if (!AnnounceService::isConfirmed($sender->getName())) {
			$event->cancel();
			SendMessage::Send($sender, "コマンドを使うには/passで機能をアクティブにする必要があります", "Pass", false);
		}
	}
}
