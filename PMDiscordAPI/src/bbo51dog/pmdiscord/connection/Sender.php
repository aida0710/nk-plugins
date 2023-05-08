<?php

declare(strict_types = 1);

namespace bbo51dog\pmdiscord\connection;

use bbo51dog\pmdiscord\exception\PMDiscordAPIException;
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

class Sender {

	/**
	 * Please do not use it from the outside.
	 *
	 * @param Webhook $webhook
	 * @param bool    $async
	 * @return void
	 * @throws PMDiscordAPIException
	 */
	public function send(Webhook $webhook, bool $async = true) : void {
		if ($async) {
			Server::getInstance()->getAsyncPool()->submitTask(new SendAsyncTask($webhook));
			return;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $webhook->getUrl());
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook->getData()));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		if ($result === false) {
			throw new PMDiscordAPIException('cURLの疎通に失敗しました');
		} elseif (!empty($result)) {
			$result_array = json_decode($result, true);
			if (in_array('code', $result_array, true) && in_array('message', $result_array, true)) {
				throw new PMDiscordAPIException("{$result_array['code']} : {$result_array['message']}");
			} else {
				Server::getInstance()->getLogger()->warning('WebHookを正常に送信できませんでした');
			}
		}
	}
}
