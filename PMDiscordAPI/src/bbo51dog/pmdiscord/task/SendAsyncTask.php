<?php

declare(strict_types = 1);

namespace bbo51dog\pmdiscord\task;

use bbo51dog\pmdiscord\connection\Sender;
use bbo51dog\pmdiscord\connection\Webhook;
use pocketmine\scheduler\AsyncTask;

class SendAsyncTask extends AsyncTask {

	/** @var Webhook */
	private Webhook $webhook;

	public function __construct(Webhook $webhook) {
		$this->webhook = $webhook;
	}

	/**
	 * @return void
	 */
	public function onRun() : void {
		(new Sender())->send($this->webhook, false);
	}

	/**
	 * @return void
	 */
	public function onCompletion() : void {
		//null
	}
}
