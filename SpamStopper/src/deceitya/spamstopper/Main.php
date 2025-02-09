<?php

declare(strict_types = 0);

namespace deceitya\spamstopper;

use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener {

    private static array $interval;
    private array $spam = [];

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        self::$interval = [];
    }

    public function onChat(PlayerChatEvent $event) {
        $name = $event->getPlayer()->getName();
        $msg = $event->getMessage();
        if (MiningLevelAPI::getInstance()->getLevel($event->getPlayer()) >= 30) {
            if (isset(self::$interval[$event->getPlayer()->getName()])) {
                SendMessage::Send($event->getPlayer(), 'チャットは1秒以内に再度送信することは出来ません', 'SpamBlock', false);
                $event->cancel();
            } else {
                self::$interval[$event->getPlayer()->getName()] = true;
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($event) : void {
                        $this->unset($event->getPlayer());
                    }
                ), 20);
            }
        } else {
            if (isset($this->spam[$name]) && $this->spam[$name] == $msg) {
                $event->cancel();
                SendMessage::Send($event->getPlayer(), '30レベル未満のプレイヤーは連続して同じメッセージは送信できません', 'SpamBlock', false);
                return;
            }
            if (isset(self::$interval[$event->getPlayer()->getName()])) {
                SendMessage::Send($event->getPlayer(), '30レベル未満のプレイヤーはチャット後5秒以内は連続してメッセージを送信できません', 'SpamBlock', false);
                $event->cancel();
            } else {
                self::$interval[$event->getPlayer()->getName()] = true;
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($event) : void {
                        $this->unset($event->getPlayer());
                    }
                ), 20 * 5);
            }
        }
        $this->spam[$name] = $msg;
    }

    public function unset(Player $player) : void {
        unset(self::$interval[$player->getName()]);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->spam[$event->getPlayer()->getName()] = false;
    }
}
