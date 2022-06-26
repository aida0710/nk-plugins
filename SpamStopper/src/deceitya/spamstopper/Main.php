<?php

namespace deceitya\spamstopper;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    private array $spam = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onChat(PlayerChatEvent $event) {
        $name = $event->getPlayer()->getName();
        $msg = $event->getMessage();
        if (MiningLevelAPI::getInstance()->getLevel($event->getPlayer()) >= 30) {
            return;
        } else {
            if (isset($this->spam[$name]) && $this->spam[$name] == $msg) {
                $event->cancel();
                $event->getPlayer()->sendMessage("§bSpamBlock §7>> §c30レベル未満のプレイヤーは連続して同じメッセージは遅れません");
                return;
            }
        }
        $this->spam[$name] = $msg;
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->spam[$event->getPlayer()->getName()] = false;
    }
}
