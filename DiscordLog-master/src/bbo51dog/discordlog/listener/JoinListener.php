<?php

declare(strict_types=1);

namespace bbo51dog\discordlog\listener;

use bbo51dog\discordlog\Main;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Content;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use function str_replace;

class JoinListener implements Listener {

	private string $url;

	public function __construct(string $url) {
		$this->url = $url;
	}

	/**
	 * @return void
	 * @priority MONITOR
	 * @ignoreCancelled
	 */
	public function onJoin(PlayerJoinEvent $event) {
		$name = $event->getPlayer()->getName();
		$time = Main::getTime();
		$s = str_replace(["%time", "%player"], [$time, $name], Main::JOIN);
		$webhook = Webhook::create($this->url);
		$content = new Content();
		$content->setText($s);
		$webhook->add($content);
		$webhook->send();
	}
}
