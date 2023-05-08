<?php

declare(strict_types = 1);

namespace bbo51dog\pmdiscord\connection;

use bbo51dog\pmdiscord\element\Element;
use bbo51dog\pmdiscord\exception\PMDiscordAPIException;
use bbo51dog\pmdiscord\task\SendAsyncTask;
use pocketmine\Server;

class Webhook {

	/** @var array */
	private array $data;

	/** @var string */
	private string $webhook_url;

	public function __construct(string $webhook_url) {
		$this->webhook_url = $webhook_url;
	}

	/**
	 * @param string $webhook_url
	 * @return self
	 */
	public static function create(string $webhook_url) : self {
		return new Webhook($webhook_url);
	}

	/**
	 * Add element
	 *
	 * @param Element $element
	 * @return $this
	 */
	public function add(Element $element) : self {
		$this->data[$element->getType()] = $element->getData();
		return $this;
	}

	/**
	 * @return array
	 */
	public function getData() : array {
		return $this->data;
	}

	/**
	 * Send Message to Discord
	 *
	 * @param bool $async
	 * @return void
	 * @throws PMDiscordAPIException
	 */
	public function send(bool $async = true) : void {
		if ($async) {
			Server::getInstance()->getAsyncPool()->submitTask(new SendAsyncTask($this));
			return;
		}
		(new Sender())->send($this, false);
	}

	/**
	 * Get webhook url
	 */
	public function getUrl() : string {
		return $this->webhook_url;
	}

	/**
	 * Change custom senders name
	 *
	 * @param string $name
	 * @return $this
	 */
	public function setCustomName(string $name) : self {
		$this->data['username'] = $name;
		return $this;
	}

	/**
	 * Set custom senders avatar url
	 *
	 * @param string $url
	 * @return $this
	 */
	public function setCustomAvatar(string $url) : self {
		$this->data['avatar_url'] = $url;
		return $this;
	}

	/**
	 * Enable|Disable tts message
	 *
	 * @param bool $tts
	 * @return $this
	 */
	public function setTts(bool $tts = true) : self {
		$this->data['tts'] = $tts;
		return $this;
	}
}
