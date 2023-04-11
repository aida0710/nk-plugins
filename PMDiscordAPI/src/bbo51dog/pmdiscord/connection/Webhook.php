<?php

declare(strict_types = 0);

namespace bbo51dog\pmdiscord\connection;

use bbo51dog\pmdiscord\element\Element;
use bbo51dog\pmdiscord\PMDiscordAPIException;
use bbo51dog\pmdiscord\task\SendAsyncTask;
use pocketmine\Server;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function in_array;
use function json_decode;
use function json_encode;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYPEER;
use const CURLOPT_URL;

class Webhook {

	private array $data;

	private string $webhook_url;

	public function __construct(string $webhook_url) {
		$this->webhook_url = $webhook_url;
	}

	public static function create(string $webhook_url) : self {
		return new Webhook($webhook_url);
	}

	/**
	 * Add element
	 */
	public function add(Element $element) : self {
		$this->data[$element->getType()] = $element->getData();
		return $this;
	}

	public function getData() : array {
		return $this->data;
	}

	/**
	 * Send Message to Discord
	 *
	 * @throws PMDiscordAPIException
	 */
	public function send(bool $async = true) : void {
		if ($async) {
			Server::getInstance()->getAsyncPool()->submitTask(new SendAsyncTask($this));
			return;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getUrl());
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->getData()));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		if ($result === false) {
			throw new PMDiscordAPIException('cURL connection failed');
		} elseif (!empty($result)) {
			$result_array = json_decode($result, true);
			if (in_array('code', $result_array, true) && in_array('message', $result_array, true)) {
				throw new PMDiscordAPIException("{$result_array['code']} : {$result_array['message']}");
			} else {
				throw new PMDiscordAPIException('Sending webhook failed');
			}
		}
	}

	/**
	 * @retutn string
	 */
	public function getUrl() : string {
		return $this->webhook_url;
	}

	/**
	 * Change custom senders name
	 */
	public function setCustomName(string $name) : self {
		$this->data['username'] = $name;
		return $this;
	}

	/**
	 * Set custom senders avatar url
	 */
	public function setCustomAvatar(string $url) : self {
		$this->data['avatar_url'] = $url;
		return $this;
	}

	/**
	 * Enable|Disable tts message
	 */
	public function setTts(bool $tts = true) : self {
		$this->data['tts'] = $tts;
		return $this;
	}
}
