<?php

declare(strict_types=1);
namespace bbo51dog\discordlog\listener;

use bbo51dog\discordlog\Main;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Content;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use function str_replace;

class WhiteListListener implements Listener {

	private string $url;

	public function __construct(string $url) {
		$this->url = $url;
	}

	/**
	 * @return void
	 * @priority MONITOR
	 * @ignoreCancelled
	 */
	public function onCommand(CommandEvent $event) {
		$command = $event->getCommand();
		if ($command === "whitelist on") {
			$message = Main::WHITELIST_ON;
		} elseif ($command === "whitelist off") {
			$message = Main::WHITELIST_OFF;
		} else {
			return;
		}
		$name = $event->getSender()->getName();
		$time = Main::getTime();
		$s = str_replace(["%time", "%player"], [$time, $name], $message);
		$webhook = Webhook::create($this->url);
		$content = new Content();
		$content->setText($s);
		$webhook->add($content);
		$webhook->send();
	}
}
