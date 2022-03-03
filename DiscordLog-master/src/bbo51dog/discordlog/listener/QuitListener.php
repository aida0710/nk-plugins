<?php

namespace bbo51dog\discordlog\listener;

use bbo51dog\discordlog\Main;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Content;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class QuitListener implements Listener {

    private string $url;

    public function __construct(string $url) {
        $this->url = $url;
    }

    /**
     * @param PlayerQuitEvent $event
     * @return void
     * @priority MONITOR
     * @ignoreCancelled
     */
    public function onQuit(PlayerQuitEvent $event) {
        $name = $event->getPlayer()->getName();
        $time = Main::getTime();
        $s = str_replace(["%time", "%player"], [$time, $name], Main::QUIT);
        $webhook = Webhook::create($this->url);
        $content = new Content();
        $content->setText($s);
        $webhook->add($content);
        $webhook->send();
    }
}
