<?php

declare(strict_types = 0);

namespace bbo51dog\pmdiscord\task;

use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\Sender;
use pocketmine\scheduler\AsyncTask;

class SendAsyncTask extends AsyncTask {

	private Webhook $webhook;

	public function __construct(Webhook $webhook) {
		$this->webhook = $webhook;
	}

	public function onRun() : void {
		Sender::send($this->webhook, false);
	}
}
