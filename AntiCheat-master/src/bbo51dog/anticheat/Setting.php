<?php

declare(strict_types = 1);
namespace bbo51dog\anticheat;

class Setting {

	private ?string $webhookUrl = null;

	private bool $enableDiscordLog = false;

	private function __construct() {
	}

	public static function loadFromArray(array $data) : self {
		$setting = new Setting();
		$setting->enableDiscordLog = (bool) $data["discord"]["enabled"];
		if ($setting->enableDiscordLog) {
			$setting->webhookUrl = $data["discord"]["url"];
		}
		return $setting;
	}

	public function getWebhookUrl() : ?string {
		return $this->webhookUrl;
	}

	public function isEnableDiscordLog() : bool {
		return $this->enableDiscordLog;
	}
}
