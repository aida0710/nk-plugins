<?php

namespace bbo51dog\anticheat;

class Setting {

    private string $antiJumpCommand;

    private ?string $webhookUrl = null;

    private bool $enableDiscordLog = false;

    private function __construct() {
    }

    public static function loadFromArray(array $data): self {
        $setting = new Setting();
        $setting->antiJumpCommand = $data["AntiJumpCommand"];
        $setting->enableDiscordLog = (bool)$data["discord"]["enabled"];
        if ($setting->enableDiscordLog) {
            $setting->webhookUrl = $data["discord"]["url"];
        }
        return $setting;
    }

    /**
     * @return string
     */
    public function getAntiJumpCommand(): string {
        return $this->antiJumpCommand;
    }

    /**
     * @return string|null
     */
    public function getWebhookUrl(): ?string {
        return $this->webhookUrl;
    }

    /**
     * @return bool
     */
    public function isEnableDiscordLog(): bool {
        return $this->enableDiscordLog;
    }
}