<?php

declare(strict_types = 0);

namespace bbo51dog\discordlog\listener;

use bbo51dog\discordlog\Main;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Content;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use function str_replace;

class MessageListener implements Listener {

    private string $url;

    public function __construct(string $url) {
        $this->url = $url;
    }

    /**
     * @return void
     * @priority MONITOR
     * @ignoreCancelled
     */
    public function onMessage(PlayerChatEvent $event) {
        $name = $event->getPlayer()->getName();
        $time = Main::getTime();
        $message = $event->getMessage();
        $s = str_replace(['%time', '%player', '%message'], [$time, $name, $message], Main::MESSAGE);
        $webhook = Webhook::create($this->url);
        $content = new Content();
        $content->setText($s);
        $webhook->add($content);
        $webhook->send();
    }
}
